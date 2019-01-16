<?php

namespace App\Http\Controllers;

use App\Classes\ResponseHelper;
use Illuminate\Http\Request;

class StaffController extends Controller
{

    /**
     * @var string
     */
    private $mTag;

    /**
     * StaffController constructor.
     */
    public function __construct()
    {
        $this->mTag = 'staff';
    }

    /**
     * Returns the persons media, image and recording
     *
     * @param $emailUri
     * @return array
     */
    public function getPersonsMedia($emailUri)
    {
        return $this->getAllMedia($emailUri, $this->mTag);
    }

    /**
     * Handles the retrieval of the audio file from the cache
     *
     * @param $emailUri
     * @param Request $request
     * @return mixed
     */
    public function getAudio($emailUri, Request $request)
    {
        $results = $this->getAudioFile($emailUri, $this->mTag);
        if (is_array($results)) {
            return $results;
        }
        if ($request->has('source')) {
            return redirect($results);
        }
        return ResponseHelper::responseBody('audio', $results, 'audio_recording');
    }

    /**
     * Handles the retrieval of the image file from the cache
     *
     * @param $emailUri
     * @param Request $request
     * @return mixed
     */
    public function getAvatar($emailUri, Request $request)
    {
        $results = $this->getAvatarImage($emailUri, $this->mTag);
        if ($request->has('source')) {
            return redirect($results);
        }
        return ResponseHelper::responseBody('image', $results, 'avatar_image');
    }

    /**
     * Handles the retrieval of the image file from the mount point.
     *
     * @param $emailUri
     * @param Request $request
     * @return mixed
     */
    public function getOfficial($emailUri, Request $request)
    {
        $results = $this->getOfficialImage($emailUri, $this->mTag);
        if ($request->has('source')) {
            return redirect($results);
        }
        return ResponseHelper::responseBody('image', $results, 'photo_id_image');
    }

}