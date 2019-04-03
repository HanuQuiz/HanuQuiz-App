<?php

    /**
     * Sample site configuration file for UserFrosting.  You should definitely set these values!
     *
     */
    return [
        'address_book' => [
            'admin' => [
                'name'  => 'Ayansh TehcnoSoft'
            ]
        ],
        'debug' => [
            'smtp' => true
        ],
        'site' => [
            'author'    =>      'Ayansh TehcnoSoft',
            'title'     =>      'Hanu-Quiz Framework',
            // URLs
            'uri' => [
                'author' => 'https://ayansh.com'
            ]
        ],
        'php' => [
            'timezone' => 'Asia/Kolkata'
        ],
        'levels' => [
            ["id" => 1, "level" => 'Easy'],
            ["id" => 2, "level" => 'Medium'],
            ["id" => 3, "level" => 'Difficult']
        ],
        'choice_types' => [
            ["id" => 1, "choice_type" => 'Single'],
            ["id" => 2, "choice_type" => 'Multiple']
        ]
    ];