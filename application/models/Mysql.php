<?php

namespace application\models;

class Mysql
{
    protected $conn = false;
    protected $sql;

    public function __construct() {
        $config = require 'application/config/db.php';
        $host = isset($config['host']) ? $config['host'] : 'localhost';
        $user = isset($config['user'])? $config['user'] : 'root';
        $password = isset($config['password'])? $config['password'] : '';
        $name = isset($config['name'])? $config['name'] : '';
        $port = isset($config['port'])? $config['port'] : '3306';
        $this->conn = mysqli_connect("$host:$port", $user, $password) or die('Ошибка подлкючения к бд');
        mysqli_select_db($this->conn,$name) or die('Ошибка выбора бд');
    }


}