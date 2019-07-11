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
    'lastname' => 'Efimov',
    'age' => 20,
];
//$queryBuilder->insert('users', $arr);
//$queryBuilder->delete('users')->where('name', '=','Dmitry');
$queryBuilder->update('users', $arr)->where('id','<','60');
$queryBuilder->execute();