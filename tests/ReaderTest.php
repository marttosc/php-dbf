<?php

namespace Marttos\Dbf\Testing;

use Marttos\Dbf\Reader;

class ReaderTest extends AbstractTestCase
{
    /**
     * @var string
     */
    private $tableName;

    /**
     * @var \Marttos\Dbf\Reader
     */
    private $reader;

    public function setUp()
    {
        parent::setUp();

        $this->tableName = dirname(__FILE__) . '/sarcontr.dbf';

        $this->reader = new Reader($this->tableName);
    }

    public function testExtensionIsDbf()
    {
        $this->assertTrue($this->reader->isDbf());
    }
}
