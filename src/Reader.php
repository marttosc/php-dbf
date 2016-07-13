<?php

namespace Marttos\Dbf;

use Marttos\Dbf\Exceptions\ReaderException;

class Reader
{
    /**
     * @var array
     */
    private $info = [];

    /**
     * @var string
     */
    private $table;

    /**
     * @var mixed
     */
    private $fp;

    /**
     * @var int
     */
    private $filePosition = 0;

    /**
     * @var int
     */
    public $version;

    /**
     * Reader constructor.
     *
     * @param $table
     * @param array $columns
     * @param null $charset
     */
    public function __construct($table, $columns = [], $charset = null)
    {
        $this->table = $table;

        $this->open();
    }

    protected function open()
    {
        if (!file_exists($this->table)) {
            throw new ReaderException(sprintf("Could not find the \"%s\" file.", $this->table));
        }

        $this->info = pathinfo($this->table);

        $this->fp = fopen($this->table, 'rb');

        $this->readHeader();

        return false !== $this->fp;
    }

    public function close()
    {
        fclose($this->fp);
    }

    public function isDbf()
    {
        return 'dbf' === strtolower($this->info['extension']);
    }

    protected function readHeader()
    {
        $this->version = $this->readChar();
    }

    protected function readByte()
    {
        return $this->readBytes(1);
    }

    protected function readBytes($b = 1)
    {
        $this->filePosition += $b;

        return fread($this->fp, $b);
    }

    protected function readChar()
    {
        $buffer = unpack('C', $this->readByte());

        return $buffer[1];
    }
}
