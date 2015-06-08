<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Package Connection
    |--------------------------------------------------------------------------
    |
    | You can set a different database connection for this package. It will set
    | new connection for model Appl. When this option is null,
    | it will connect to the main database, which is set up in database.php
    |
    */

    'connection' => null,

    /*
    |--------------------------------------------------------------------------
    | Slug Separator
    |--------------------------------------------------------------------------
    |
    | Here you can change the slug separator. This is very important in matter
    | of magic method __call() and also a SlugableTrait. The default value
    | is a dot.
    |
    */

    'separator' => '.',

    /*
    |--------------------------------------------------------------------------
    | Appls and Allowedappl "Purport"
    |--------------------------------------------------------------------------
    |
    | You can purport or simulate package behavior no matter what is in your
    | database. It is really useful when you are testing you application.
    | Set up what will methods uses() and allowedAppl() return.
    |
    */

    'purport' => [

        'enabled' => false,

        'options' => [
            'uses'       => true,
            'allowed'   => true,
        ],

    ],

];
