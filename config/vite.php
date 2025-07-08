<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Vite Manifest Path
    |--------------------------------------------------------------------------
    |
    | Ini adalah path ke file manifest Vite Anda. Kita arahkan langsung ke
    | public_html karena file build Anda ada di sana (bukan di public/ Laravel).
    |
    */

    'manifest_path' => base_path('../public_html/build/manifest.json'),

    /*
    |--------------------------------------------------------------------------
    | Laravel Vite Plugin Options
    |--------------------------------------------------------------------------
    |
    | Opsi tambahan yang digunakan plugin Vite Laravel untuk menentukan
    | build path & resolusi file secara otomatis.
    |
    */

    'build_directory' => 'build',

    'dev_server' => [
        'url' => env('VITE_DEV_SERVER_URL', 'http://localhost:5173'),
    ],

];
