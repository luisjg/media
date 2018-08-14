<?php

namespace App\Http\Controllers;

use App\Classes\ResponseHelper;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\RejectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FacultyController extends Controller
{

    /**
     * @var string
     */
    private $mTag;

    /**
     * FacultyController constructor.
     */
    public function __construct()
    {
        $this->mTag = 'faculty';
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
        return $this->getAudioFile($emailUri, $this->mTag);
    }

    /**
     * Handles the retrieval of the image file from the cache
     *
     * @param $emailUri
     * @return mixed
     */
    public function getAvatar($emailUri)
    {
        return $this->getAvatarImage($emailUri, $this->mTag);
    }

    /**
     * Handles the retrieval of the image file from the mount point.
     *
     * @param $emailUri
     * @return mixed
     */
    public function getOfficial($emailUri)
    {
        return $this->getOfficialImage($emailUri, $this->mTag);
    }
}