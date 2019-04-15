<?php

    /**
     * Sample site configuration file for UserFrosting.  You should definitely set these values!
     *
     */
    return [
        /*
        * ----------------------------------------------------------------------
        * Mail Service Config
        * ----------------------------------------------------------------------
        * See https://learn.userfrosting.com/mail/the-mailer-service
        */
        'mail'    => [
            'mailer'          => 'smtp', // Set to one of 'smtp', 'mail', 'qmail', 'sendmail'
            'host'            => getenv('SMTP_HOST') ?: null,
            'port'            => 587,
            'auth'            => true,
            'secure'          => 'tls',
            'username'        => getenv('SMTP_USER') ?: null,
            'password'        => getenv('SMTP_PASSWORD') ?: null,
            'smtp_debug'      => 4,
            'message_options' => [
                'CharSet'   => 'UTF-8',
                'isHtml'    => true,
                'Timeout'   => 15
            ]
        ]
    ];