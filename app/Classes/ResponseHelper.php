<?php

namespace App\Classes;

use Illuminate\Support\MessageBag;

class ResponseHelper
{

    /**
     * Builds the response array header
     *
     * @param string $success 'true' | 'false'
     * @param string $status HTTP status code
     * @return array
     */
    private static function responseHeader($success, $status = '200')
    {
        return [
            'success' => $success,
            'status' => $status,
            'api' => 'media',
            'version' => '1.0'
        ];
    }

    /**
     * Returns the generic error response array
     *
     * @return array
     */
    public static function error()
    {
        $response = self::responseHeader('false', '404');
        $response['message'][] = 'Something went wrong with the web service.';
        return $response;
    }

    /**
     * Returns the upload success array
     *
     * @param string $email the person's email
     * @return array
     */
    public static function uploadSuccess($email)
    {
        $response = self::responseHeader('true');
        $response['message'][] = 'Image successfully uploaded for '.$email;
        return $response;
    }

    /**
     * Returns the general results array
     *
     * @param string $type collection type
     * @param array $results the results array
     * @return array
     */
    public static function results($type, $results)
    {
        $response = self::responseHeader('true');
        $response['collection'] = $type;
        $response['count'] = strval(count($results));
        $response['media'] = $results;
        return $response;
    }

    /**
     * Returns the cache success message array
     *
     * @return array
     */
    public static function cache()
    {
        $response = self::responseHeader('true');
        $response['message'][] = 'Cache deleted successfully.';
        return $response;
    }


    /**
     * Returns the failed validator response.
     *
     * @param MessageBag $messages
     * @return array
     */
    public static function failedValidation($messages)
    {
        $response = self::responseHeader('false', '404');
        $response['messages'] = $messages->all();
        return $response;
    }

    /**
     * @param $message
     * @return array
     */
    public static function customErrorMessage($message)
    {
        $response = self::responseHeader('false', '404');
        $response['messages'] = $message;
        return $response;
    }
}