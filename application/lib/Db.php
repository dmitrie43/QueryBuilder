<?php

namespace application\lib;

use PDO;

class Db
{
    protected $db;
    protected $sql;
    protected $where;
    protected $fields = [];

    public function __construct() {
        $config = require 'application/config/db.php';
        $this->db = new PDO('mysql:host='.$config['host'].';dbname='.$config['name'].'', $config['user'], $config['password']);

//        $this->getFields();
    }

//    public function getFields() {
//        $sql = "DESC ". $this->table;
//    }

    public function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $stmt->bindValue(':'.$key, $value);
            }
        }
        $stmt->execute();
        return $stmt;
    }

    public function execute() {
        $result = $this->sql.$this->where;
//        debug($result);
        $stmt = $this->db->prepare($result);
        $stmt->execute();
        return $stmt;
    }

    private function setSql($sql) {
        $this->sql = $sql;
    }

    public function insert($table, $list) {
        $field_list = '';
        $value_list = '';
        foreach ($list as $k => $v) {
//            if (in_array($k, $this->fields)) {
                $field_list .= $k . ',';
                $value_list .= "'" . $v . "'" . ',';
//            }
        }
        $field_list = rtrim($field_list, ',');
        $value_list = rtrim($value_list, ',');
        $sql = "INSERT INTO {$table} ({$field_list}) VALUES ($value_list)";
        $this->setSql($sql);
    }

    public function delete($table) {
        $sql = "DELETE FROM {$table}";
        $this->setSql($sql);
        return $this;
    }

    public function where($value1 = [], $cond = [], $value2 = []) {
        if (!empty($value1) && !empty($cond) && !empty($value2)) {
            $where = rtrim(" WHERE $value1 $cond \"$value2\"");
            $this->where = $where;
            return $this;
        } else {
            exit('Условие не полное');
        }
    }



//    public function viewAll($sql, $params = []) {
//        $result = $this->query($sql, $params);
//        return $result->fetchAll(PDO::FETCH_ASSOC);
//    }
//
//    public function column($sql, $params = []) {
//        $result = $this->query($sql, $params);
//        return $result->fetchColumn();
//    }
}