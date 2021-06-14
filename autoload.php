<?php
spl_autoload_register(function($class)
{
    $class = strtr($class, [
        'App\\' => '',
        '\\' => '/'
    ]);
    require "{$class}.php";
});