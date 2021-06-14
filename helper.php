<?php
/*
    * @ ZippyShare Cracker
    * @ Version 1.0
    * @ Created by Muhammad Randika Rosyid
*/

function curl($url)
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => 0,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36 Edg/91.0.864.41',
        CURLOPT_RETURNTRANSFER => TRUE
    ]);

    return curl_exec($ch);
}

function validUrl($url)
{
    return filter_var($url, FILTER_VALIDATE_URL);
}

function input()
{
    return trim(fgets(STDIN));
}

function callClass($class, $param = NULL)
{
    (!empty($param)) ? new $class($param) : new $class;
}

function outputColor($text, $mode)
{
    $dataMode = [
        'error' => function($text) {
            return (new App\Library\ClicoText($text))->background()->red()->bold();
        },
        'success' => function($text) {
            return (new App\Library\ClicoText($text))->green()->bold();
        },
        'danger' => function($text) {
            return (new App\Library\ClicoText($text))->red()->bold();
        },
        'warning' => function($text) {
            return (new App\Library\ClicoText($text))->yellow()->bold();
        },
        'alertSuccess' => function($text) {
            return (new App\Library\ClicoText($text))->background()->green()->bold();
        },
        'alertBlue' => function($text) {
            return (new App\Library\ClicoText($text))->background()->blue()->bold();
        },
        'alertWarning' => function($text) {
            return (new App\Library\ClicoText($text))->background()->yellow()->bold();
        },
    ];

    return $dataMode[$mode]($text);
}