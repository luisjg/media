<?php

namespace App\Http\Controllers;

use App\Classes\ResponseHelper;
use Illuminate\Support\Facades\Storage;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Handles the retrieval of the image file from the cache
     *
     * @param $emailUri
     * @param $type
     * @return mixed
     */
    public function getAvatarImage($emailUri, $type)
    {
        $fileDestination = 'media/'.$type.'/'.$emailUri.'/';
        if (Storage::exists($fileDestination.'avatar.jpg')) {
            return redirect(Storage::url($fileDestination.'avatar.jpg'));
        } else {
           return redirect(env('OFFICIAL_PHOTO_LOCATION'));
        }
        $response = ResponseHelper::error();
        return $response;
    }

    /**
     * Handles the retrieval of the image file from the mount point.
     *
     * @param $emailUri
     * @param $type
     * @return array
     */
    public function getOfficialImage($emailUri, $type)
    {
        $fileDestination = 'media/'.$type.'/'.$emailUri.'/';
        if (Storage::exists($fileDestination.'official.jpg')) {
            return redirect(Storage::url($fileDestination.'official.jpg'));
        } else {
            return redirect(env('OFFICIAL_PHOTO_LOCATION'));
        }
        $response = ResponseHelper::error();
        return $response;
    }
}
