<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS
    |--------------------------------------------------------------------------
    |
    | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
    | to accept any value.
    |
    */

    'supportsCredentials' => false,
    'allowedOrigins' => ['*'],
    'allowedHeaders' => ['X-Accept-Charset,X-Accept,Content-Type,Credentials'],
    'allowedMethods' => ['POST, GET, PUT, OPTIONS, PATCH, DELETE'],
    'exposedHeaders' => [],
    'maxAge' => 0,
];
