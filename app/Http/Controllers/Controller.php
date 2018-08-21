<?php

namespace App\Http\Controllers;

use App\Classes\ResponseHelper;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\RejectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    /**
     * Returns the persons media, image and recording
     *
     * @param $emailUri
     * @param $type
     * @return array
     */
    protected function getAllMedia($emailUri, $type)
    {
        $recording = $this->getAudioUrl($emailUri, $type);
        $avatar = $this->getAvatarImageUrl($emailUri, $type);
        $official = $this->getOfficialImageUrl($emailUri, $type);
        $response = $this->buildResponse();
        $response['count'] = strval(count([$avatar, $recording, $official]));
        $results = [
            'audio_recording' => $recording,
            'avatar_image' => $avatar,
            'official_image' => $official
        ];
        if ($type === 'student') {
            $likeness = [
                'likeness_image' => $this->getLikenessImageUrl($emailUri, $type)
            ];
            $response['count'] = strval(count([$avatar, $recording, $official, $likeness]));
            $results = array_merge($results, $likeness);
        }
        $response['media'][] = $results;
        return response()->json($response);
    }

    /**
     * Handles the retrieval of the audio file from the cache
     *
     * @param $emailUri
     * @param $type
     * @return mixed
     */
    protected function getAudioFile($emailUri, $type)
    {
        if (Cache::has($emailUri.':audio')) {
            return redirect(Cache::get($emailUri.':audio'), 301);
        }
        $email = $emailUri.'@my.csun.edu';

        if ($type === 'faculty' || $type === 'staff') {
            $email = $emailUri.'@csun.edu';
        }

        $url = env('NAMECOACH_API_URL').
            '?auth_token='.
            env('NAMECOACH_API_SECRET').
            '&email_list='.$email;
        $result = $this->executeGuzzleCall($url);
        if (!empty($result['data'][0])) {
            if ($result['data'][0]['recording_link']) {
                $nameRecording = $result['data'][0]['recording_link'];
                Cache::add($emailUri.':audio', $nameRecording, env('APP_CACHE_DURATION'));
                return redirect($nameRecording)->header('Content-Type', 'audio/mpeg');
            }
        }
        return ResponseHelper::customErrorMessage('Resource was not found for '.$emailUri);
    }

    /**
     * Builds the response JSON header
     *
     * @param string $type
     * @return array
     */
    protected function buildResponse($type = 'media')
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
                'version' => '1.1',
                'collection' => $type
            ];
        }
        return $response;
    }

    /**
     * Executes the Guzzle call to the APIs
     *
     * @param $url
     * @param $method
     * @return \Illuminate\Support\Collection|mixed
     */
    private function executeGuzzleCall($url)
    {
        $options = [
            'verify' => false
        ];
        $client = new Client();
        try {
            $response = $client->post($url, $options);
            $data = json_decode($response->getBody(), true);
        } catch (RejectionException $e) {
            $data = $this->buildResponse('error');
        }
        return $data;
    }

    /**
     * Handles the retrieval of the image file from the cache
     *
     * @param $emailUri
     * @param $type
     * @return mixed
     */
    protected function getAvatarImage($emailUri, $type)
    {
        $fileDestination = 'media/'.$type.'/'.$emailUri.'/';
        if (Storage::exists($fileDestination.'avatar.jpg')) {
            return redirect(Storage::url($fileDestination.'avatar.jpg'))->header('Content-Type', 'image/jpeg');
        } else {
            return redirect(Storage::url('profile-default.png'))->header('Content-Type', 'image/png');
        }
    }

    /**
     * Handles the retrieval of the image file from the mount point.
     *
     * @param $emailUri
     * @param $type
     * @return array
     */
    protected function getOfficialImage($emailUri, $type)
    {
        $fileDestination = 'media/'.$type.'/'.$emailUri.'/';
        if (Storage::exists($fileDestination.'official.jpg')) {
            return redirect(Storage::url($fileDestination.'official.jpg'))->header('Content-Type', 'image/jpeg');
        } else {
            return redirect(Storage::url('profile-default.png'))->header('Content-Type', 'image/png');
        }
    }

    /**
     * @param $emailUri
     * @param $type
     * @return array|\Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    protected function getLikenessImage($emailUri, $type)
    {
        $fileDestination = 'media/'.$type.'/'.$emailUri.'/';
        if (Storage::exists($fileDestination.'likeness.jpg')) {
            return redirect(Storage::url($fileDestination.'likeness.jpg'))->header('Content-Type', 'image/jpeg');
        } else {
            return redirect(Storage::url('profile-default.png'))->header('Content-Type', 'image/png');
        }
    }

    /**
     * Returns the individuals audio url
     *
     * @param $emailUri
     * @param $type
     * @return string
     */
    protected function getAudioUrl($emailUri, $type)
    {
        return url('/1.1/'.$type.'/media/'.$emailUri.'/audio');
    }

    /**
     * Returns the individuals avatar image url
     *
     * @param $emailUri
     * @param $type
     * @return string
     */
    protected function getAvatarImageUrl($emailUri, $type)
    {
        return url('/1.1/'.$type.'/media/'.$emailUri.'/avatar');
    }

    /**
     * Returns the individuals official image url
     *
     * @param $emailUri
     * @param $type
     * @return string
     */
    protected function getOfficialImageUrl($emailUri, $type)
    {
        return url('/1.1/'.$type.'/media/'.$emailUri.'/official');
    }

    /**
     * @param $emailUri
     * @param $type
     * @return string
     */
    protected function getLikenessImageUrl($emailUri, $type)
    {
        return url('/1.1/'.$type.'/media/'.$emailUri.'/likeness');
    }

    /**
     * Deletes the application cache
     * @return array
     */
    public function clearImageAndAudioFromCache()
    {
        Cache::clear();
        $response = ResponseHelper::cache();
        return $response;
    }
}
