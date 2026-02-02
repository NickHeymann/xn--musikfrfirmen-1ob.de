<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google_sheets' => [
        'event_requests_id' => env('GOOGLE_SHEETS_EVENT_REQUESTS_ID'),
        'bookings_id' => env('GOOGLE_SHEETS_BOOKINGS_ID', env('GOOGLE_SHEETS_EVENT_REQUESTS_ID')), // Falls separate Sheet, sonst gleiche wie Event Requests
        'credentials_path' => env('GOOGLE_SHEETS_CREDENTIALS_PATH'),
        // Alternative: individual service account credentials
        'project_id' => env('GOOGLE_SHEETS_PROJECT_ID'),
        'private_key_id' => env('GOOGLE_SHEETS_PRIVATE_KEY_ID'),
        'private_key' => env('GOOGLE_SHEETS_PRIVATE_KEY'),
        'client_email' => env('GOOGLE_SHEETS_CLIENT_EMAIL'),
        'client_id' => env('GOOGLE_SHEETS_CLIENT_ID'),
    ],

    'event_request' => [
        'recipients' => env('EVENT_REQUEST_RECIPIENTS', 'kontakt@xn--musikfrfirmen-1ob.de,moin@nickheymann.de,moin@jonasglamann.de'),
    ],

];
