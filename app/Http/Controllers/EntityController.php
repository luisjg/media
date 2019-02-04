<?php

namespace App\Http\Controllers;

use App\Classes\ResponseHelper;
use Illuminate\Http\Request;

class EntityController extends Controller
{

    /**
     * FacultyController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Returns the persons media, image and recording
     *
     * @param $emailUri
     * @param $type
     * @return array
     */
    public function getPersonsMedia($emailUri, $type)
    {
        return $this->getAllMedia($emailUri, $type);
    }

    /**
     * Handles the retrieval of the audio file from the cache
     *
     * @param $emailUri
     * @param $type
     * @param Request $request
     * @return mixed
     */
    public function getAudio($emailUri,$type, Request $request)
    {
        $results = $this->getAudioFile($emailUri, $type);
        if (is_array($results)) {
            return $results;
        }
        if ($request->has('source') && ($request->get('source') == TRUE)) {
            return redirect($results);
        }
        return ResponseHelper::responseBody('audio', $results, 'audio_recording');
    }

    /**
     * Handles the retrieval of the image file from the cache
     *
     * @param $emailUri
     * @param $type
     * @param Request $request
     * @return mixed
     */
    public function getAvatar($emailUri, $type, Request $request)
    {
        $results = $this->getAvatarImage($emailUri, $type);
        if ($request->has('source') && ($request->get('source') == TRUE)) {
            return redirect($results);
        }
        return ResponseHelper::responseBody('image', $results, 'avatar_image');

    }

    /**
     * Handles the retrieval of the image file from the mount point.
     *
     * @param $emailUri
     * @param $type
     * @param Request $request
     * @return mixed
     */
    public function getOfficial($emailUri, $type, Request $request)
    {
        $results = $this->getOfficialImage($emailUri, $type);
        if ($request->has('source') && ($request->get('source') == TRUE)) {
            return redirect($results);
        }
        return ResponseHelper::responseBody('image', $results, 'photo_id_image');

    }

    /**
     * Handles the retrieval of the image file from the mount point.
     *
     * @param $emailUri
     * @param $type
     * @param Request $request
     * @return mixed
     */
    public function getLikeness($emailUri, $type, Request $request)
    {
        $results = $this->getLikenessImage($emailUri, $type);
        if ($request->has('source') && ($request->get('source') == TRUE)) {
            return redirect($results);
        }
        return ResponseHelper::responseBody('image', $results, 'likeness_image');

    }
}
