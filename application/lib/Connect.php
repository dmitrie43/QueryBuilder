<?php

namespace application\lib;

use PDO;

class Connect
{
    private $db;

    public function __construct() {
        $config = require 'application/config/db.php';
        $this->db = new PDO('mysql:host='.$config['host'].';dbname='.$config['name'].'', $config['user'], $config['password']);
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