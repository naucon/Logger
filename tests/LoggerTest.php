<?php
/*
 * Copyright 2015 Sven Sanzenbacher
 *
 * This file is part of the naucon package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Naucon\Logger\Tests;

use Naucon\Logger\Logger;
use Naucon\Logger\LogRecord;
use Naucon\Logger\Tests\Handler\LogHandler;

class LoggerTest extends \Psr\Log\Test\LoggerInterfaceTest
{
    /**
     * @var         LogHandler
     */
    private $logHandler = null;

    /**
     * @return      LoggerInterface
     */
    public function getLogger()
    {
        $this->logHandler = new LogHandler();

        $loggerObject = new Logger();
        $loggerObject->addHandler($this->logHandler);
        return $loggerObject;
    }

    /**
     * This must return the log messages in order with a simple formatting: "<LOG LEVEL> <MESSAGE>"
     *
     * Example ->error('Foo') would yield "error Foo"
     *
     * @return      string
     */
    public function getLogs()
    {
        $logs = array();
        $logRecords = $this->logHandler->getRecords();
        foreach ($logRecords as $logRecord) {
            /**
             * @var LogRecord   $logRecord
             */
            $logs[] = $logRecord->getLevel() . ' ' . $logRecord->getMessage();
        }
        return $logs;
    }
}