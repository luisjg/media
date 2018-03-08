<?php

namespace App\Http\Controllers;

use App\Classes\ResponseHelper;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\RejectionException;
use Illuminate\Support\Facades\Cache;

class MediaController extends Controller
{

    /**
     * MediaController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', [
            'only' => [
                'storeImage'
            ]
        ]);
    }

    /**
     * Returns the index page for the web service
     *
     * @return view
     */
    public function index()
    {
        $emailUri = 'steven.fitzgerald';
        if (env('APP_ENV') !== 'production') {
            $emailUri = 'nr_'.$emailUri;
        }
        return view('pages.landing.index', compact('emailUri'));
    }

    /**
     * Returns the persons media, image and recording
     *
     * @param $emailUri
     * @return array
     */
    public function getFacultyMedia($emailUri)
    {
        $results = [
            'audio' => $this->getAudioUrl($emailUri),
            'avatar' => $this->getImageUrl($emailUri)
        ];
        $response = ResponseHelper::results('media', $results);
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
        $response = ResponseHelper::error();
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
        if (Cache::has($emailUri.':avatar')) {
            return redirect(Cache::get($emailUri.':avatar'));
        }
        $email = $emailUri.'@csun.edu';
        $url = env('DIRECTORY_WS_URL').'/members?email='.$email;
        $result = $this->executeGuzzleCall($url);
        $profileImage = null;
        if (array_key_exists('people', $result)) {
            $profileImage = $result['people']['profile_image'];
            Cache::add($emailUri.':avatar', $profileImage, env('APP_CACHE_DURATION'));
            return redirect($profileImage);
        }
        $response = ResponseHelper::error();
        return $response;
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
     * Returnts the individuals image url
     *
     * @param $emailUri
     * @return string
     */
    private function getImageUrl($emailUri)
    {
        return url('/api/1.0/'.$emailUri.'/avatar');
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

    /**
     * Handles the storing of the person's email
     *
     * @param Request $request
     * @param $emailUri
     * @return array
     */
    public function storeImage(Request $request, $emailUri)
    {
        try {
            $request->file('profile_image')
                ->move(env('UPLOAD_IMAGE_LOCATION'.$emailUri), 'avatar.jpg');
        } catch (FileException $e) {
            return ResponseHelper::error();
        }
    }
}
