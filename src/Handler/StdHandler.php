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

use Naucon\File\File;
use Naucon\File\FileWriter;
use Naucon\Logger\LogRecord;
use Naucon\Logger\LogLevel;
use Naucon\Logger\FormatHelper;
use Naucon\Logger\Handler\HandlerAbstract;
use Naucon\Logger\Handler\Exception\HandlerException;

/**
 * Standard Handler Class
 *
 * @package    Logger
 * @subpackage Handler
 * @author     Sven Sanzenbacher
 */
class StdHandler extends HandlerAbstract
{
    /**
     * @access      protected
     * @var         File                    file object
     */
    protected $fileObject = null;



    /**
     * Constructor
     *
     * @param       string|File\\SplFileInfo                path to cache directory
     * @param       string                  log level
     */
    public function __construct($pathname, $level=LogLevel::DEBUG)
    {
        if ($pathname instanceof File) {
            $fileObject = $pathname;
        } elseif ($pathname instanceof \SplFileInfo) {
            $fileObject = new File($pathname);
        } else {
            $fileObject = new File($pathname);
        }
        $this->setFileObject($fileObject);

        parent::__construct($level);
    }



    /**
     * @access      protected
     * @return      File                    file object
     */
    protected function getFileObject()
    {
        return $this->fileObject;
    }

    /**
     * @access      protected
     * @param       File                    file object
     * @return      void
     */
    protected function setFileObject(File $fileObject)
    {
        $this->fileObject = $fileObject;
    }

    /**
     * @access      protected
     * @param       LogRecord
     * @return      void
     */
    protected function processRecord(\Naucon\Logger\LogRecord $logRecord)
    {
        $this->writeRecord($this->formatRecord($logRecord));
    }

    /**
     * @access      protected
     * @param       LogRecord
     * @return      string
     */
    protected function formatRecord(LogRecord $logRecord)
    {
        $formatHelper = new FormatHelper();

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
     * @param       string                  log record string
     * @return      void
     */
    protected function writeRecord($logRecordString)
    {
        $fileWriterObject = new FileWriter($this->getFileObject(), 'a');
        $fileWriterObject->write($logRecordString);
    }
}