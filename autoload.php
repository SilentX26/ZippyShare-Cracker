<?php
/*
    * @ ZippyShare Cracker
    * @ Version 1.0
    * @ Created by Muhammad Randika Rosyid
*/

spl_autoload_register(function($class)
{
    $class = strtr($class, [
        'App\\' => '',
        '\\' => '/'
    ]);
    require "{$class}.php";
});