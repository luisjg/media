<?php

namespace App\Http\Controllers;

use App\Classes\ResponseHelper;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\RejectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EntityController extends Controller
{

    /**
     * @var string
     */
    // private $mTag;

    /**
     * FacultyController constructor.
     */
    public function __construct()
    {
        // $this->mTag = 'faculty';
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
     * @return mixed
     */
    public function getAudio($emailUri,$type)
    {
        $results = $this->getAudioFile($emailUri, $type);
        if (is_array($results)) {
            return $results;
        }
        return ResponseHelper::responseBody('audio', $results, 'audio_recording');
    }

    /**
     * Handles the retrieval of the image file from the cache
     *
     * @param $emailUri
     * @param $type
     * @return mixed
     */
    public function getAvatar($emailUri, $type)
    {
        $results = $this->getAvatarImage($emailUri, $type);
        return ResponseHelper::responseBody('image', $results, 'avatar_image');
    }

    /**
     * Handles the retrieval of the image file from the mount point.
     *
     * @param $emailUri
     * @param $type
     * @return mixed
     */
    public function getOfficial($emailUri, $type)
    {
        $results = $this->getOfficialImage($emailUri, $type);
        return ResponseHelper::responseBody('image', $results, 'photo_id_image');
    }
}
