<?php

    /**
     * Sample site configuration file for UserFrosting.  You should definitely set these values!
     *
     */
    return [
        'site' => [
            'registration' => [
                'require_email_verification' => false
            ],
            'analytics' => [
                'google' => [
                    'enabled'   => false
                ]
            ]
        ],
        'settings' => [
            'displayErrorDetails' => true
        ],
        'csrf' => [
            'blacklist' => [
                '^/sync/api/Artifacts' => [
                    'POST'
                ]
            ]
        ]
    ];