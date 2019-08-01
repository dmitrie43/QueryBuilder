<?php

namespace application\lib;

use PDO;
use application\lib\BuilderFactory;

class Connect
{
    private $db;

    public function __construct() {
        $dbms = new BuilderFactory();
        $config = require 'application/config/db.php';
        $this->db = new PDO($dbms->getDbms().':host='.$config['host'].';dbname='.$config['name'].'', $config['user'], $config['password']);
    }

    public function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
        }
        $stmt->execute();
        return $stmt;
    }

    public function prepared($result) {
        $stmt = $this->db->prepare($result);
        $stmt->execute();
        return $stmt;
    }
}