<?php
/*
 * Copyright 2015 Sven Sanzenbacher
 *
 * This file is part of the naucon package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Naucon\Logger\Tests\Handler;

use Naucon\Logger\LogRecord;
use Naucon\Logger\Handler\HandlerAbstract;
use Naucon\Logger\Handler\Exception\HandlerException;

class LogHandler extends HandlerAbstract
{
    /**
     * @access      protected
     * @var         array
     */
    protected $logRecords = array();

    /**
     * @access      protected
     * @param       LogRecord       $logRecord
     * @return      void
     */
    protected function processRecord(\Naucon\Logger\LogRecord $logRecord)
    {
        $this->logRecords[] = $logRecord;
    }

    /**
     * @return      array                   log records
     */
    public function getRecords()
    {
        return $this->logRecords;
    }

    /**
     * @return      void
     */
    public function clear()
    {
        $this->logRecords = array();
    }
}