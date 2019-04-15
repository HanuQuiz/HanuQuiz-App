<?php

    /**
     * Sample site configuration file for UserFrosting.  You should definitely set these values!
     *
     */
    return [
        'address_book' => [
            'admin' => [
                'name'  => 'Ayansh TechnoSoft'
            ]
        ],
        'debug' => [
            'smtp' => false
        ],
        'site' => [
            'author'    =>      'Ayansh TechnoSoft',
            'title'     =>      'Hanu-Quiz Framework',
            // URLs
            'uri' => [
                'author' => 'https://ayansh.com'
            ],
            'registration' => [
                'require_email_verification' => true
            ],
            'analytics' => [
                'google' => [
                    'code'      => 'UA-52130169-3',
                    'enabled'   => true
                ]
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
        ],
        'settings' => [
            'displayErrorDetails' => false
        ],
        'csrf' => [
            'blacklist' => [
                '^/sync/api/Artifacts' => [
                    'POST'
                ]
            ]
        ]
    ];