<?php
/*
    * @ ZippyShare Cracker
    * @ Version 1.0
    * @ Created by Muhammad Randika Rosyid
*/

// Fungsi ini akan menangani class yang dipanggil
// Lalu memanggil file class tersebut
spl_autoload_register(function($class)
{
    $class = strtr($class, [
        'App\\' => '',
        '\\' => '/'
    ]);
    require "{$class}.php";
});