<?php

namespace App\Http\Controllers;

use App\Classes\ResponseHelper;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\RejectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic;

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
     * @param Request $request
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
            'audio_recording' => $recording,
            'avatar_image' => $image,
            'photo_id_image' => $official
        ];
        return $response;
    }

    /**
     * Handles the retrieval of the audio file from the cache
     *
     * @param $emailUri
     * @param bool $student
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
        $result = $this->executeGuzzleCall($url);
        if (!empty($result['data'][0])) {
            if ($result['data'][0]['recording_link']) {
                $nameRecording = $result['data'][0]['recording_link'];
                Cache::add($emailUri.':audio', $nameRecording, env('APP_CACHE_DURATION'));
                return redirect($nameRecording);
            }
        }
        return ResponseHelper::error();
    }

    /**
     * Handles the retrieval of the image file from the cache
     *
     * @param $emailUri
     * @return mixed
     */
    public function getPersonsAvatarImage(Request $request, $emailUri)
    {
        return $this->getAvatarImage($emailUri, 'faculty');
    }

    /**
     * Handles the retrieval of the image file from the mount point.
     *
     * @param $emailUri
     * @return array
     */
    public function getPersonsOfficialImage(Request $request, $emailUri)
    {
        return $this->getOfficialImage($emailUri, 'faculty');
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
        return ResponseHelper::cache();
    }

    /**
     * Handles the storing of the person's image
     *
     * @param Request $request
     * @param $emailUri
     * @return array
     */
    public function storeImage(Request $request, $emailUri)
    {
        if (is_string($request->profile_image)) {
            $results = $this->validateBase64Image($request);
            if (is_bool($results)) {
                return $this->saveBase64ImageToS3($request, $emailUri);
            } else {
                return $results;
            }
        } else {
            $results = $this->validateImageFile($request);
            if (is_bool($results)) {
                return $this->saveImageFileToS3($request, $emailUri);
            } else {
                return $results;
            }
        }
    }

    /**
     * @param Request $request
     * @param $emailUri
     * @return array
     */
    private function saveImageFileToS3(Request $request, $emailUri)
    {
        $fileDestination = 'media/'. $request->get('entity_type').
            '/'.$emailUri;
        $result = Storage::disk('local')->putFileAs(
            $fileDestination,
            $request->file('profile_image'),
            $request->file('profile_image')->getClientOriginalName()
        );

        if (is_string($result)) {
            return ResponseHelper::uploadSuccess($emailUri);
        } else {
            return ResponseHelper::error();
        }
    }


    /**
     * @param Request $request
     * @param $emailUri
     * @return array
     */
    private function saveBase64ImageToS3(Request $request, $emailUri)
    {
        $fileDestination = 'media/'.
            $request->get('entity_type').
            '/'.$emailUri.'/'.$request->get('image_type').'.jpg';
        $image = ImageManagerStatic::make($request->profile_image)->resize(200,200);


        $result = Storage::put($fileDestination,  $image->stream()->__toString(), 'public');

        if ($result) {
            return ResponseHelper::uploadSuccess($emailUri);
        } else {
            return ResponseHelper::error();
        }
    }

    /**
     * @param Request $request
     * @return array|bool
     */
    private function validateImageFile(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'entity_type' => 'required|string',
                'profile_image' => 'required|image'
            ],
            [
                'required' => 'Please make sure you have included a valid :attribute field.'
            ]
        );

        if ($validator->fails()) {
            return ResponseHelper::failedValidation($validator->messages());
        }

        return true;
    }

    /**
     * @param Request $request
     * @return array|bool
     */
    private function validateBase64Image(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'entity_type' => 'required|string',
                'profile_image' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (!base64_decode($value)) {
                            return $fail('Please include '.$attribute. ' it is required.');
                        }
                    }],
            ],
            [
                'required' => 'Please make sure you have included a valid :attribute field.'
            ]
        );

        if ($validator->fails()) {
            return ResponseHelper::failedValidation($validator->messages());
        }

        return true;
    }
}