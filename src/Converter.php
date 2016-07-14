<?php

namespace Marttos\DbfSql;

use XBase\Table;
use Illuminate\Support\Collection;

abstract class Converter implements ConverterInterface
{
    /**
     * @var \XBase\Table
     */
    private $table;

    /**
     * @var \Illuminate\Support\Collection
     */
    private $info;

    /**
     * @var \Illuminate\Support\Collection
     */
    private $columns;

    /**
     * Converter constructor.
     *
     * @param string $tableName
     * @param array $columns
     * @param string $charset
     */
    public function __construct($tableName, $columns = [], $charset = null)
    {
        if (0 == count($columns)) {
            $columns = null;
        }

        $this->table = new Table($tableName, $columns, $charset);

        $this->columns = new Collection($this->table()->columns);

        $this->info = new Collection(pathinfo($tableName));
    }

    /**
     * Get the table reader.
     *
     * @return Table
     */
    public function table()
    {
        return $this->table;
    }

    /**
     * Get the file info.
     *
     * @return Collection
     */
    public function info()
    {
        return $this->info;
    }

    /**
     * Get the columns.
     *
     * @return Collection
     */
    public function columns()
    {
        return $this->columns;
    }

    public function create()
    {
        //
    }

    public function insert()
    {
        //
    }

    public function drop()
    {
        //
    }

    public function getReservedKeywords()
    {
        //
    }

    public function setReservedKeywords(array $keywords)
    {
        //
    }
}
