<?php

return [
    'AD' => env('AD', false),
    'adServer' => env('ADSERVER'),
    'port' => env('PORT'),
    'domain' => env('DOMAIN'),
    'numLoginAttempt' => env('NUM_LOGIN_ATTEMPT',3),
];
