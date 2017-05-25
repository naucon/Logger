<?php
/*
 * Copyright 2015 Sven Sanzenbacher
 *
 * This file is part of the naucon package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Naucon\Logger\Handler;

use Naucon\File\FileWriter;
use Naucon\Logger\LogRecord;
use Naucon\Logger\FormatHelper;

/**
 * Standard Error Handler Class
 *
 * @package    Logger
 * @subpackage Handler
 * @author     Sven Sanzenbacher
 */
class StderrHandler extends HandlerAbstract
{
    /**
     * @access      protected
     * @param       LogRecord       $logRecord
     * @return      void
     */
    protected function processRecord(LogRecord $logRecord)
    {
        $this->writeRecord($this->formatRecord($logRecord));
    }

    /**
     * @access      protected
     * @param       LogRecord       $logRecord
     * @return      string
     */
    protected function formatRecord(LogRecord $logRecord)
    {
        $logRecordString = '[' . date('d-M-Y H:i:s e', round($logRecord->getCreated(), 0)) . '] '
            . 'PHP '
            . strtolower($logRecord->getLevel()) . ': '
            . $logRecord->getMessage();

        $context = $logRecord->getContext();
        if (isset($context['exception'])
            && $context['exception'] instanceof \Exception) {

            /**
             * @var \Exception $exception
             */
            $exception = $context['exception'];
            $logRecordString.= ' in ' . $exception->getFile() . ':' . $exception->getLine();
            $logRecordString.= PHP_EOL;
            $logRecordString.= 'Stack trace:' .  PHP_EOL;
            $logRecordString.= $exception->getTraceAsString();
        }
        $logRecordString.= PHP_EOL;

        return $logRecordString;
    }

    /**
     * @access      protected
     * @param       string      $logRecordString        log record string
     * @return      void
     */
    protected function writeRecord($logRecordString)
    {
        $fileWriterObject = new FileWriter(ini_get('error_log'), 'a');
        $fileWriterObject->write($logRecordString);
    }
}