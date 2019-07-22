<?php

namespace application\lib;

use application\lib\Connect;

class QueryBuilder
{
    protected $sql;
    protected $where;
    protected $limit;
    protected $orderBy;

    public function __construct() {
    	$this->connect = new Connect();
    }

    public function execute()
    {
        $result = $this->sql.$this->where.$this->orderBy.$this->limit;
        return $this->connect->query($result);
    }

    private function setSql($sql) {
        $this->sql = $sql;
    }

    public function insert($table, $list) {
        $field_list = implode(',', array_keys($list));
        $value_list = implode(',', array_map(function($value){return var_export($value, true);}, array_values($list)));
        $sql = "INSERT INTO {$table} ({$field_list}) VALUES ($value_list)";
        $this->setSql($sql);
    }

    public function delete($table) {
        $sql = "DELETE FROM {$table}";
        $this->setSql($sql);
        return $this;
    }

    public function where($column = '', $cond = '', $value = '') {
        if (empty($column) && empty($cond) && empty($value)) {
            exit('WHERE не полное');
        }
        if ($this->checkCondition($cond)) {
            $where = rtrim(" WHERE $column $cond \"$value\"");
            $this->where = $where;
            return $this;
        } else {
            exit('Знак в WHERE неверен');
        }
    }
    //Проверка на правильность ввода знака
    private function checkCondition($code) {
        $array = ['=', '>', '<', '<=', '>='];
        return in_array($code, $array);
    }

    public function update($table, $list) {
        $uplist = '';
        foreach ($list as $key => $value) {
            $uplist .= "$key = '$value'".",";
        }
        $uplist = rtrim($uplist, ',');
        $sql = "UPDATE $table SET $uplist";
        $this->setSql($sql);
        return $this;
    }

    public function limit($num) {
        if ($num < 0) {
            exit('LIMIT должен быть цифрой больше и равен нулю');
        }
        $limit = rtrim(" LIMIT $num");
        $this->limit = $limit;
        return $this;
    }

    public function select($list, $table) {
        $fields = " '".implode(" ','", $list). "' ";
        $sql = "SELECT $fields FROM $table";
        $this->setSql($sql);
        return $this;
    }

    public function orderBy($list, $order) {
        $fields = '';
        if ($this->checkOrder($order) == true) {
            foreach ($list as $value) {
                $fields .= "$value,";
            }
            $fields = rtrim($fields, ',');
            $orderBy = rtrim(" ORDER BY $fields $order");
            $this->orderBy = $orderBy;
            return $this;
        } else {
            exit('Неверная сортировка');
        }
    }
    //Проверка на правильность ввода ASC, DESC
    private function checkOrder($order) {
        return ($order == 'ASC' || $order == 'DESC');
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