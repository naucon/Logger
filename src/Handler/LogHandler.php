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
 * Log Handler Class
 *
 * @package    Logger
 * @subpackage Handler
 * @author     Sven Sanzenbacher
 */
class LogHandler extends HandlerAbstract
{
    /**
     * @access      protected
     * @var         float                   unix timestamp with microseconds
     */
    protected $logTimeStart;

    /**
     * @access      protected
     * @var         float                   unix timestamp with microseconds
     */
    protected $logTimeEnd;

    /**
     * @access      protected
     * @var         File                    file object
     */
    protected $fileObject = null;

    /**
     * @access      protected
     * @var         bool                    include client ip address in log record
     */
    protected $includeClientIp = false;

    /**
     * @access      protected
     * @var         bool                    include user in log record, the user have to be set by calling setUser()
     */
    protected $includeUser = false;

    /**
     * @access      protected
     * @var         string                  user name or id
     */
    protected $user = null;

    /**
     * @access      protected
     * @var         string                  client ip
     */
    protected $clientIp = null;


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
        $this->setLogTimeStart();

        parent::__construct($level);
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->setLogTimeEnd();
        unset($this->fileObject);
    }



    /**
     * @access      protected
     * @return      int
     */
    protected function getLogTimeStart()
    {
        return $this->logTimeStart;
    }

    /**
     * @access      protected
     * @return      void
     */
    protected function setLogTimeStart()
    {
        $this->logTimeStart = microtime(true);
    }

    /**
     * @access      protected
     * @return      int
     */
    protected function getLogTimeEnd()
    {
        return $this->logTimeEnd;
    }

    /**
     * @access      protected
     * @return      void
     */
    protected function setLogTimeEnd()
    {
        $this->logTimeEnd = microtime(true);
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
     * include client ip address in log record
     *
     * @param       bool        true = include client ip address, false = exclude client ip address
     * @return      void
     */
    public function includeClientIp($include)
    {
        $this->includeClientIp = (bool)$include;
    }

    /**
     * include user in log record, the user have to be set by calling setUser()
     *
     * @param       bool        true
     * @return      void
     */
    public function includeUser($include)
    {
        $this->includeUser = (bool)$include;
    }

    /**
     * @return      string          user name or id
     */
    public function getUser()
    {
        if (is_null($this->user)) {
            $user = 'anonym';
        } else {
            $user = $this->user;
        }
        return $user;
    }

    /**
     * @param       string          user name or id
     * @return      void
     */
    public function setUser($user)
    {
        if (empty($user)) {
            $this->user = null;
        } else {
            $this->user = (string)$user;
        }
    }

    /**
     * @return      string          ip address
     */
    public function getClientIp()
    {
        if (is_null($this->clientIp)) {
            if ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) )
            {
                $this->clientIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            elseif ( isset($_SERVER['HTTP_CLIENT_IP']) )
            {
                $this->clientIp = $_SERVER['HTTP_CLIENT_IP'];
            }
            elseif ( isset($_SERVER['REMOTE_ADDR']) )
            {
                $this->clientIp = $_SERVER['REMOTE_ADDR'];
            }
            else
            {
                $this->clientIp = 'unavailable';
            }
        }
        return $this->clientIp;
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
        $duration = round($logRecord->getCreated() - $this->getLogTimeStart(), 3);

        $formatHelper = new FormatHelper();

        $logRecordString = $formatHelper->pad(date('Y-m-d H:i:s', round($logRecord->getCreated(), 0)), 21, ' ')
            . $formatHelper->pad($duration, 12, ' ')
            . $formatHelper->pad(strtoupper($logRecord->getLevel()), 10, ' ');
        if ($this->includeClientIp) {
            $logRecordString.= $formatHelper->pad('[' . $this->getClientIp() . ']', 20, ' ');
        }
        if ($this->includeUser) {
            $logRecordString.= $formatHelper->pad($this->getUser(), 10, ' ');
        }
        $logRecordString.= $logRecord->getMessage();

        $context = $logRecord->getContext();
        if (isset($context['exception'])
            && $context['exception'] instanceof \Exception) {

            /**
             * @var \Exception $exception
             */
            $exception = $context['exception'];
            $logRecordString.= ' in ' . $exception->getFile() . ':' . $exception->getLine();
            $logRecordString.= PHP_EOL;
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