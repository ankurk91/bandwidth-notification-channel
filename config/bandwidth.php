<?php

return [
    /**
     * The unique ID associated with your Bandwidth account.
     * @source https://dev.bandwidth.com/guides/accountCredentials.html#messaging
     */
    'account_id' => env('BANDWIDTH_ACCOUNT_ID'),

    /**
     * The ID of the Application to which your "from number" is associated in the Bandwidth Phone Number Dashboard.
     * Application must have at least one location associated.
     */
    'application_id' => env('BANDWIDTH_APPLICATION_ID'),

    /**
     * Username and password to be used for Basic Authorization.
     */
    'api_username' => env('BANDWIDTH_API_USERNAME'),
    'api_password' => env('BANDWIDTH_API_PASSWORD'),

    /**
     * One of your telephone numbers; the message should sent from.
     * It must follow the E.164 format, for example +19195551212
     */
    'from' => env('BANDWIDTH_FROM', null),

    /**
     * This option allows to you test this channel without calling the actual API.
     * When it is set to `true`, messages will be written to Laravel log files
     * instead of being sent to the actual recipient.
     */
    'dry_run' => (bool) env('BANDWIDTH_DRY_RUN', false),

    /**
     * @source http://docs.guzzlephp.org/en/stable/request-options.html
     */
    'http_options' => [
        'debug' => (bool) env('BANDWIDTH_DEBUG_HTTP_CLIENT', false),
    ],
];
