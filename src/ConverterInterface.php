<?php

namespace Marttos\DbfSql;

use XBase\Table;
use Illuminate\Support\Collection;

interface ConverterInterface
{
    /**
     * Get a SQL create statement.
     *
     * @return string
     */
    public function create();

    /**
     * Get a SQL insert statements.
     *
     * @return mixed
     */
    public function insert();

    /**
     * Get a SQL drop statement.
     *
     * @return string
     */
    public function drop();

    /**
     * Get the reserved keywords.
     *
     * @return Collection
     */
    public function getReservedKeywords();

    /**
     * Set the reserved keywords.
     *
     * @param array $keywords
     * @return void
     */
    public function setReservedKeywords(array $keywords);

    /**
     * Get the table reader.
     *
     * @return Table
     */
    public function table();

    /**
     * Get the file info.
     *
     * @return Collection
     */
    public function info();

    /**
     * Get the columns.
     *
     * @return Collection
     */
    public function columns();
}
