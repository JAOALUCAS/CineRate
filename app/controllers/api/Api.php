<?php

namespace App\controllers\api;

class Api{

    private static $apiAuthorization;

    public static function setConfig($apiAuthorization)
    {

        self::$apiAuthorization = $apiAuthorization;

    }

    public static function getDetails()
    {

        $key = self::$apiAuthorization;

        $apiClient = new \GuzzleHttp\Client();

        $apiContent = $apiClient->request('GET', 'https://api.themoviedb.org/3/discover/movie', [
            'headers' => [
                'Authorization' => "Bearer {$key}",
                'accept' => 'application/json',
            ],
        ]);

        echo $apiClient;

    }

}