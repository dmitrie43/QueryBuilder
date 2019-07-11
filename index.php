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
$arr2 = [
    'name', 'lastname'
];
//$queryBuilder->insert('users', $arr);
//$queryBuilder->delete('users')->where('name', '=','Dmitry')->limit(2);
//$queryBuilder->update('users', $arr)->where('id','<','60');
$queryBuilder->select($arr2, 'users')->where('name', '>=', 'Joker')->limit(5);
$queryBuilder->execute();