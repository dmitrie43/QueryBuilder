<?php
/**
 * Created by PhpStorm.
 * User: dmitr
 * Date: 01.08.2019
 * Time: 13:41
 */

namespace application\lib;

class BuilderFactory
{
    protected $dbms;
    public $mysql = 'mysql';
    public $pg = 'pgsql';

    public function __construct() {
        $config = require 'application/config/db.php';
        $dbms = strtolower($config['dbms']);
        switch ($dbms) {
            case $this->mysql:
                $this->dbms = $this->mysql;
                break;
            case $this->pg:
                $this->dbms = $this->pg; //Для записи в pdo
                break;
            default:
                return false;
        }
    }

    public function getDbms()
    {
        return $this->dbms;
    }
}