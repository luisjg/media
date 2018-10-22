<?php

namespace App\Http\Controllers;

use App\Classes\ResponseHelper;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\RejectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{

    /**
     * @var string
     */
    private $mTag;

    /**
     * StudentController constructor.
     */
    public function __construct()
    {
        $this->mTag = 'student';
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
     * @return mixed
     */
    public function getAudio($emailUri)
    {
        $results = $this->getAudioFile($emailUri, $this->mTag);
        if (is_array($results)) {
            return $results;
        }
        return ResponseHelper::responseBody('audio', $results, 'audio_recording');
    }

    /**
     * Handles the retrieval of the image file from the cache
     *
     * @param $emailUri
     * @return mixed
     */
    public function getAvatar($emailUri)
    {
        $results = $this->getAvatarImage($emailUri, $this->mTag);
        return ResponseHelper::responseBody('image', $results, 'avatar_image');
    }

    /**
     * Handles the retrieval of the image file from the mount point.
     *
     * @param $emailUri
     * @return mixed
     */
    public function getOfficial($emailUri)
    {
        $results = $this->getOfficialImage($emailUri, $this->mTag);
        return ResponseHelper::responseBody('image', $results, 'photo_id_image');
    }

    /**
     * Handles the retrieval of the image file from the mount point.
     *
     * @param $emailUri
     * @return mixed
     */
    public function getLikeness($emailUri)
    {
        $results = $this->getLikenessImage($emailUri, $this->mTag);
        return ResponseHelper::responseBody('image', $results, 'likeness_image');
    }
}