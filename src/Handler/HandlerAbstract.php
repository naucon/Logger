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

use Naucon\Logger\LogRecord;
use Naucon\Logger\Handler\HandlerInterface;
use Naucon\Logger\Handler\Exception\HandlerException;
use Psr\Log\AbstractLogger as PsrAbstractLogger;
use Psr\Log\LogLevel;

/**
 * Abstract Handler Class
 *
 * @abstract
 * @package    Logger
 * @subpackage Handler
 * @author     Sven Sanzenbacher
 */
abstract class HandlerAbstract extends PsrAbstractLogger implements HandlerInterface
{
    /**
     * @access      protected
     * @var         array                   log level priorities
     */
    protected $priorities = array(
        LogLevel::EMERGENCY => 0,
        LogLevel::ALERT     => 1,
        LogLevel::CRITICAL  => 2,
        LogLevel::ERROR     => 3,
        LogLevel::WARNING   => 4,
        LogLevel::NOTICE    => 5,
        LogLevel::INFO      => 6,
        LogLevel::DEBUG     => 7,
    );

    /**
     * @access      protected
     * @var         string                  log level
     */
    protected $logLevel = null;

    /**
     * @access      protected
     * @var         int                     log level priority
     */
    protected $logLevelPriority = null;



    /**
     * Constructor
     *
     * @param       string                  log level
     */
    public function __construct($level=LogLevel::DEBUG)
    {
        $this->setLogLevel($level);
    }

    /**
     * @return      string                  log level
     */
    public function getLogLevel()
    {
        return $this->logLevel;
    }

    /**
     * @access      protected
     * @param       string                  log level
     * @return      void
     */
    protected function setLogLevel($logLevel)
    {
        $this->setLogLevelPriority($this->getPriority($logLevel));
        $this->logLevel = (string)$logLevel;
    }

    /**
     * @return      string                  log level
     */
    public function getLogLevelPriority()
    {
        return $this->logLevelPriority;
    }

    /**
     * @access      protected
     * @param       string                  log level
     * @return      void
     */
    protected function setLogLevelPriority($logLevelPriority)
    {
        $this->logLevelPriority = (int)$logLevelPriority;
    }

    /**
     * @param       string                  log level
     * @return      int                     log level priority
     */
    public function getPriority($level)
    {
        if (array_key_exists((string)$level,$this->priorities)) {
            $priority = $this->priorities[(string)$level];
        } else {
            throw new HandlerException('Log handler was given a unkown log level.');
        }
        return $priority;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param       mixed $level
     * @param       string $message
     * @param       array $context
     * @return      void
     */
    public function log($level, $message, array $context=array())
    {
        if ($this->getLogLevelPriority() >= $this->getPriority($level)) {
            $logRecord = new LogRecord($level, $message, $context);
            $this->processRecord($logRecord);
        }
    }

    /**
     * @abstract
     * @access      protected
     * @param       LogRecord
     * @return      void
     */
    abstract protected function processRecord(\Naucon\Logger\LogRecord $logRecord);
}