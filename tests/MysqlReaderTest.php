<?php

namespace Marttos\DbfSql\Testing;

use Marttos\DbfSql\MysqlConverter;

class MysqlReaderTest extends AbstractTestCase
{
    /**
     * @var MysqlConverter
     */
    private $reader;

    public function setUp()
    {
        parent::setUp();

        $file = dirname(__FILE__) . '/../dbf/customer.dbf';

        $this->reader = new MysqlConverter($file);
    }

    public function testInitializeTable()
    {
        $this->assertNotNull($this->reader->table());
    }

    public function testFirstColumnName()
    {
        $this->assertEquals('custno', $this->reader->columns()->first()->name);
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->reader->table()->close();
    }
}
