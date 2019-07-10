<?php

require 'application/lib/Dev.php';

use application\lib\Db;

spl_autoload_register(function($class) {
    $path = str_replace('\\', '/', $class.'.php');
    if (file_exists($path)) {
        require $path;
    }
});

session_start();

//$params = [
//            'name' => 'Дмитрий',
//        ];

$queryBuilder = new Db;
$arr = [
    'name' => 'Dmitry',
];
//$queryBuilder->insert('users', $arr);
//$queryBuilder->delete('users')->where('name', '=','Toy');
$queryBuilder->execute();