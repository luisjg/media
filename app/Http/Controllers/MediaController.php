<?php

namespace App\Http\Controllers;

use App\Classes\ResponseHelper;
use http\Env\Response;
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
     * @param $emailUri
     * @return array
     */
    public function getPersonsMedia($emailUri)
    {
        $recording = $this->getAudioFileUrl($emailUri);
        $avatar = $this->getAvatarImageFileUrl($emailUri);
        $official = $this->getOfficialImageFileUrl($emailUri);
        $response = $this->buildResponse();
        $response['count'] = (string)count([$avatar, $recording, $official]);
        $response['media'][] = [
            'audio_recording' => $recording,
            'avatar_image' => $avatar,
            'photo_id_image' => $official,
        ];
        return response()->json($response);
    }

    /**
     * Handles the retrieval of the image file from the cache
     *
     * @param $emailUri
     * @return mixed
     */
    public function getPersonsAvatarImage($emailUri)
    {
        return redirect($this->getAvatarImage($emailUri, 'faculty'));
    }

    /**
     * Handles the retrieval of the image file from the mount point.
     *
     * @param $emailUri
     * @return array
     */
    public function getPersonsOfficialImage($emailUri)
    {
        return redirect($this->getOfficialImage($emailUri, 'faculty'));
    }

    /**
     * @param $emailUri
     * @return mixed
     */
    public function getPersonsAudio($emailUri)
    {
        $result = $this->getAudioFile($emailUri, 'faculty');
        if (is_array($result)) {
            return $result;
        }
        return redirect($this->getAudioFile($emailUri, 'faculty'));
    }

    /**
     * Returns the individuals audio url
     *
     * @param $emailUri
     * @return string
     */
    private function getAudioFileUrl($emailUri)
    {
        return url('1.0/'.$emailUri.'/audio');
    }

    /**
     * Returns the individuals avatar image url
     *
     * @param $emailUri
     * @return string
     */
    private function getAvatarImageFileUrl($emailUri)
    {
        return url('1.0/'.$emailUri.'/avatar');
    }

    /**
     * Returns the individuals official image url
     *
     * @param $emailUri
     * @return string
     */
    private function getOfficialImageFileUrl($emailUri)
    {
        return url('1.0/'.$emailUri.'/official');
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
            '/'.$emailUri.'/'.$request->image_name.'.jpg';
        $image = ImageManagerStatic::make($request->file('profile_image'))->resize(200,200);
        $result = Storage::put($fileDestination,  $image->stream()->__toString(), 'public');
        if ($result) {
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
                'profile_image' => 'required|image',
                'image_name' => 'required|string'
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
                'image_type' => 'required|string'
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
     * @return array
     */
    public function deleteImage(Request $request)
    {
        // validate values
        $validator = Validator::make($request->all(),
            [
                'entity_type' => 'required|string',
                'image_name' => 'required|string',
                'email' => 'required|email'
            ],
            [
                'required' => 'Looks like you forgot to include :attribute field.'
            ]
        );
        if ($validator->fails()) {
            return ResponseHelper::failedValidation($validator->messages());
        }
        $emailUri = strtok($request->email, '@');
        $filePath = 'media/'.$request->entity_type .'/'.$emailUri.'/'.$request->image_name.'.jpg';
        $deleted = false;
        $message = ucfirst($request->image_name).' image could not deleted!';
        // check the image & delete if exists
        if (Storage::exists($filePath)) {
            $deleted = Storage::delete($filePath);
            $message = ucfirst($request->image_name).' image was successfully deleted for '.$emailUri;
        }
        $response = [$deleted, $message];
        return response()->json($response);
    }
}