<?php
/**
 * Created by PhpStorm.
 * User: dmitr
 * Date: 01.08.2019
 * Time: 11:39
 */

namespace application\dbms;

use application\lib\QueryBuilder;

class MysqlBuilder extends QueryBuilder
{
    public function wrap($sql) {
        return str_replace('`','\'', $sql);
    }
}