<?php

namespace application\lib;

use PDO;
use application\lib\BuilderFactory;
use PDOException;

class Connect
{
    private $db;

    public function __construct() {
        $config = require 'application/config/db.php';
        try {
            $this->db = new PDO($config['driver'].':host='.$config['host'].';dbname='.$config['name'].'', $config['user'], $config['password']);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            exit('Подключение не удалось: '. $e->getMessage());
        }
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