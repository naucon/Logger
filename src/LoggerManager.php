<?php
/*
 * Copyright 2015 Sven Sanzenbacher
 *
 * This file is part of the naucon package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Naucon\Logger;

use Psr\Log\LoggerInterface as PsrLoggerInterface;

/**
 * Logger Manager Class
 *
 * @package    Logger
 * @author     Sven Sanzenbacher
 */
class LoggerManager
{
    /**
     * @static
     * @access      private
     * @var         LoggerManager
     */
    static private $singletonObject = null;

    /**
     * @access      private
     * @var         Logger
     */
    private $loggerObject = null;


    /**
     * Constructor
     *
     * @access      private
     * @param       PsrLoggerInterface     $loggerObject
     */
    private function __construct(PsrLoggerInterface $loggerObject)
    {
        $this->setLoggerObject($loggerObject);
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        unset($this->loggerObject);
    }

    /**
     * Clone
     *
     * @access    private
     * @return    void
     */
    private function __clone()
    {
    }

    /**
     * singleton
     *
     * @static
     * @param       PsrLoggerInterface      $loggerObject
     * @return      void
     */
    static public function init(PsrLoggerInterface $loggerObject)
    {
        self::$singletonObject = new self($loggerObject);
    }



    /**
     * @return      PsrLoggerInterface
     */
    private function getLoggerObject()
    {
        return $this->loggerObject;
    }

    /**
     * @param       PsrLoggerInterface      $loggerObject
     * @return      void
     */
    private function setLoggerObject(PsrLoggerInterface $loggerObject)
    {
        $this->loggerObject = $loggerObject;
    }

    /**
     * System is unusable.
     *
     * @static
     * @param       string      $message
     * @param       array       $context
     * @return      void
     */
    static public function emergency($message, array $context = array())
    {
        if (!is_null(self::$singletonObject)) {
            self::$singletonObject->getLoggerObject()->emergency($message, $context);
        }
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @static
     * @param       string      $message
     * @param       array       $context
     * @return      void
     */
    static public function alert($message, array $context = array())
    {
        if (!is_null(self::$singletonObject)) {
            self::$singletonObject->getLoggerObject()->alert($message, $context);
        }
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @static
     * @param       string      $message
     * @param       array       $context
     * @return      void
     */
    static public function critical($message, array $context = array())
    {
        if (!is_null(self::$singletonObject)) {
            self::$singletonObject->getLoggerObject()->critical($message, $context);
        }
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @static
     * @param       string      $message
     * @param       array       $context
     * @return      void
     */
    static public function error($message, array $context = array())
    {
        if (!is_null(self::$singletonObject)) {
            self::$singletonObject->getLoggerObject()->error($message, $context);
        }
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @static
     * @param       string      $message
     * @param       array       $context
     * @return      void
     */
    static public function warning($message, array $context = array())
    {
        if (!is_null(self::$singletonObject)) {
            self::$singletonObject->getLoggerObject()->warning($message, $context);
        }
    }

    /**
     * Normal but significant events.
     *
     * @static
     * @param       string      $message
     * @param       array       $context
     * @return      void
     */
    static public function notice($message, array $context = array())
    {
        if (!is_null(self::$singletonObject)) {
            self::$singletonObject->getLoggerObject()->notice($message, $context);
        }
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @static
     * @param       string      $message
     * @param       array       $context
     * @return      void
     */
    static public function info($message, array $context = array())
    {
        if (!is_null(self::$singletonObject)) {
            self::$singletonObject->getLoggerObject()->info($message, $context);
        }
    }

    /**
     * Detailed debug information.
     *
     * @static
     * @param       string      $message
     * @param       array       $context
     * @return      void
     */
    static public function debug($message, array $context = array())
    {
        if (!is_null(self::$singletonObject)) {
            self::$singletonObject->getLoggerObject()->debug($message, $context);
        }
    }

    /**
     * Logs with an arbitrary level.
     *
     * @static
     * @param       mixed       $level
     * @param       string      $message
     * @param       array       $context
     * @return      void
     */
    static public function log($level, $message, array $context = array())
    {
        if (!is_null(self::$singletonObject)) {
            self::$singletonObject->getLoggerObject()->log($level, $message, $context);
        }
    }
}