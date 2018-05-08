<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\RejectionException;
use Illuminate\Support\Facades\Cache;

class MediaController extends Controller
{

    /**
     * Returns the persons media, image and recording
     *
     * @param $emailUri
     * @return array
     */
    public function getPersonsMedia($emailUri)
    {
        $recording = $this->getAudioUrl($emailUri);
        $image = $this->getImageUrl($emailUri);
        $official = $this->getOfficialImageUrl($emailUri);
        $response = $this->buildResponse();
        $response['count'] = strval(count([$image, $recording, $official]));
        $response['media'][] = [
            'audio' => $recording,
            'avatar' => $image,
            'photo_id' => $official
        ];
        return $response;
    }

    /**
     * Handles the retrieval of the audio file from the cache
     *
     * @param $emailUri
     * @return mixed
     */
    public function getPersonsAudio($emailUri)
    {
        if (Cache::has($emailUri.':audio')) {
            return redirect(Cache::get($emailUri.':audio'));
        }
        $email = $emailUri.'@csun.edu';
        $url = env('NAMECOACH_API_URL').
            '?auth_token='.
            env('NAMECOACH_API_SECRET').
            '&email_list='.$email;
        $result = $this->executeGuzzleCall($url, 'post');
        $nameRecording = null;
        if (array_key_exists(0, $result['data'])) {
            $nameRecording = $result['data'][0]['recording_link'];
            Cache::add($emailUri.':audio', $nameRecording, env('APP_CACHE_DURATION'));
            return redirect($nameRecording);
        }
        $response = $this->buildResponse('error');
        return $response;
    }

    /**
     * Handles the retrieval of the image file from the cache
     *
     * @param $emailUri
     * @return mixed
     */
    public function getPersonsImage($emailUri)
    {
        if(Cache::has($emailUri.':avatar')) {
            return redirect(Cache::get($emailUri.':avatar'));
        }
        $email = $emailUri.'@csun.edu';
        $url = env('DIRECTORY_WS_URL').'/api/members?email='.$email;
        $result = $this->executeGuzzleCall($url);
        $profileImage = null;
        if(array_key_exists('people', $result)) {
            $profileImage = $result['people']['profile_image'];
            Cache::add($emailUri.':avatar', $profileImage, env('APP_CACHE_DURATION'));
            return redirect($profileImage);
        }
        $response = $this->buildResponse('error');
        return $response;
    }

    /**
     * Handles the retrieval of the image file from the mount point.
     *
     * @param $emailUri
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function getPersonsOfficialImage($emailUri)
    {
        return redirect(env('OFFICIAL_PHOTO_LOCATION'));
    }

    /**
     * Executes the Guzzle call to the APIs
     *
     * @param $url
     * @param $method
     * @return \Illuminate\Support\Collection|mixed
     */
    private function executeGuzzleCall($url, $method = 'get')
    {
        $options = [
            'verify' => false
        ];

        $client = new Client();
        try {
            if($method == 'post') {
                $response = $client->post($url);
            } else {
                $response = $client->get($url, $options);
            }
            $data = json_decode($response->getBody(), true);
        } catch (RejectionException $e) {
            $data = collect();
        }
        return $data;
    }

    /**
     * Builds the response JSON header
     *
     * @param string $type
     * @return array
     */
    private function buildResponse($type = 'media')
    {
        if ($type === 'error') {
            $response = [
                'success' => 'false',
                'status' => '404',
                'api' => 'media',
                'version' => '1.0',
                'message' => 'Something went wrong with the web service.'
            ];
        } else if ($type === 'success') {
            $response = [
                'Success' => 'true',
                'status' => '200',
                'api' => 'media',
                'version' => '1.0',
                'message' => 'Cache deleted successfully.'
            ];
        } else {
            $response = [
                'success' => 'true',
                'status' => '200',
                'api' => 'media',
                'version' => '1.0',
                'collection' => $type
            ];
        }
        return $response;
    }


    /**
     * Returns the individuals audio url
     *
     * @param $emailUri
     * @return string
     */
    private function getAudioUrl($emailUri)
    {
        return url('/api/1.0/'.$emailUri.'/audio');
    }

    /**
     * Returns the individuals avatar image url
     *
     * @param $emailUri
     * @return string
     */
    private function getImageUrl($emailUri)
    {
        return url('/api/1.0/'.$emailUri.'/avatar');
    }

    /**
     * Returns the individuals official image url
     *
     * @param $emailUri
     * @return string
     */
    private function getOfficialImageUrl($emailUri)
    {
        return url('/api/1.0/'.$emailUri.'/official');
    }

    /**
     * Deletes the application cache
     * @return array
     */
    public function clearImageAndAudioFromCache()
    {
        Cache::clear();
        $response = $this->buildResponse('success');
        return $response;
    }
}
