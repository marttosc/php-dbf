<?php

namespace Marttos\DbfSql;

class MysqlConverter extends Converter
{
    /**
     * MysqlConverter constructor.
     *
     * @param string $tableName
     * @param array $columns
     * @param string $charset
     */
    public function __construct($tableName, array $columns = [], $charset = null)
    {
        parent::__construct($tableName, $columns, $charset);
    }
}
