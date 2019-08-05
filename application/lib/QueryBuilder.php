<?php

namespace application\lib;

use application\dbms\MysqlBuilder;
use application\dbms\PgBuilder;
use application\lib\Connect;

abstract class QueryBuilder
{
    protected $sql;
    protected $where;
    protected $limit;
    protected $orderBy;
    protected $connect;
    protected $andWhere;
    protected $orWhere;

    abstract protected function wrap($name);

    public function __construct() {
    	$this->connect = new Connect();
    }

    public function execute() {
        $res = $this->sql.$this->where.$this->andWhere.$this->orWhere.$this->orderBy.$this->limit;
        var_dump($res);
        return $this->connect->query($res);
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
    //Проверка условия
    private function checkWhere($column, $cond, $value) {
        if (empty($column) || empty($cond) || empty($value)) {
            exit('WHERE не полное');
        }
        if ($this->checkCondition($cond)) {
            return;
        } else {
            exit('Знак в WHERE неверен');
        }
    }

    public function where($column = '', $cond = '', $value = '') {
        $this->checkWhere($column, $cond, $value);
        $wrapped = $this->wrap($column);
        $value = var_export($value, true);
        $where = rtrim(" WHERE $wrapped $cond $value");
        $this->where = $where;
        return $this;
    }

    public function andWhere($column = '', $cond = '', $value = '') {
        $this->checkWhere($column, $cond, $value);
        $wrapped = $this->wrap($column);
        $value = var_export($value, true);
        $where = rtrim(" AND $wrapped $cond $value");
        $this->andWhere = $where;
        return $this;
    }

    public function orWhere($column = '', $cond = '', $value = '') {
        $this->checkWhere($column, $cond, $value);
        $wrapped = $this->wrap($column);
        $value = var_export($value, true);
        $where = rtrim(" OR $wrapped $cond $value");
        $this->orWhere = $where;
        return $this;
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

    private function setSql($sql) {
        $this->sql = $sql;
    }
}