<?php

return [
    'ffmpeg' => [
        'binaries' => env('FFMPEG_BINARIES', 'G:\Courses\web\BackEnd\Laravel Packages\laravel backages\ffmpeg-20200626-7447045-win64-static\bin\ffmpeg.exe'),
        'threads'  => 12,
    ],

    'ffprobe' => [
        'binaries' => env('FFPROBE_BINARIES', 'G:\Courses\web\BackEnd\Laravel Packages\laravel backages\ffmpeg-20200626-7447045-win64-static\bin\ffprobe.exe'),
    ],

    'timeout' => 3600,

    'enable_logging' => true,
];
