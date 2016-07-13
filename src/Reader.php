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
     * @var bool
     */
    public $isFoxPro = false;

    /**
     * @var int
     */
    public $version;

    /**
     * @var int
     */
    public $lastUpdate;

    /**
     * @var int
     */
    public $records = 0;

    /**
     * @var int
     */
    public $headerLength = 0;

    /**
     * @var int
     */
    public $recordLength = 0;

    /**
     * @var bool
     */
    public $inTransaction = false;

    /**
     * @var bool
     */
    public $encrypted = false;

    /**
     * @var mixed
     */
    public $mdx;

    /**
     * @var mixed
     */
    public $languageCode;

    /**
     * @var array
     */
    public $columns = [];

    /**
     * Reader constructor.
     *
     * @param string $table
     * @param array $columns
     * @param mixed $charset
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
        $this->isFoxPro = in_array($this->version, [48, 49, 245, 251]);
        $this->lastUpdate = $this->readLastUpdate();
        $this->records = $this->readInt();
        $this->headerLength = $this->readShort();
        $this->recordLength = $this->readShort();

        $this->reserved();

        $this->inTransaction = 0 != $this->readByte();
        $this->encrypted = 0 != $this->readByte();

        /*
         * The first four bytes are for free record thread and the last eight bytes are reserved for multi-user dBASE.
         */
        $this->readBytes(12);

        $this->mdx = $this->readByte();
        $this->languageCode = $this->readByte();

        $this->reserved();

        if ($this->headerLength > filesize($this->table) || !$this->isDbf()) {
            throw new ReaderException(sprintf("%s is not a DBF file!", $this->table));
        }
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
        return $this->read('C');
    }

    protected function readShort()
    {
        return $this->read('S', 2);
    }

    protected function readInt()
    {
        return $this->read('I', 4);
    }

    protected function readLong()
    {
        return $this->read('L', 8);
    }

    protected function readLastUpdate()
    {
        $year = $this->read('c');
        $month = $this->read('c');
        $day = $this->read('c');

        return mktime(0, 0, 0, $month, $day, ($year > 70 ? 1900 + $year : 2000 + $year));
    }

    protected function reserved($bytes = 2)
    {
        return $this->readBytes($bytes);
    }

    private function read($format, $bytes = 1)
    {
        $buffer = unpack(strtoupper($format), $this->readBytes($bytes));

        return $buffer[1];
    }
}
