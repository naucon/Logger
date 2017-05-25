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

use Naucon\Logger\Exception\InvalidArgumentException;
use Psr\Log\AbstractLogger as PsrAbstractLogger;
use Psr\Log\LoggerInterface as PsrLoggerInterface;

/**
 * Abstract Logger Class
 *
 * @abstract
 * @package    Logger
 * @author     Sven Sanzenbacher
 */
abstract class LoggerAbstract extends PsrAbstractLogger implements LoggerInterface
{
    /**
     * @access      protected
     * @var         array
     */
    protected $logHandler = array();

    /**
     * @var         array                   log levels
     */
    protected $logLevels = array(
        LogLevel::EMERGENCY,
        LogLevel::ALERT,
        LogLevel::CRITICAL,
        LogLevel::ERROR,
        LogLevel::WARNING,
        LogLevel::NOTICE,
        LogLevel::INFO,
        LogLevel::DEBUG
    );

    /**
     * return the registered log handlers
     *
     * @return      array|PsrLoggerInterface
     */
    public function getHandlers()
    {
        return $this->logHandler;
    }

    /**
     * register a log handle to handle log messages
     *
     * @param       PsrLoggerInterface      $logHandlerObject
     * @return      void
     */
    public function addHandler(PsrLoggerInterface $logHandlerObject)
    {
        $this->logHandler[] = $logHandlerObject;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param       mixed       $level          $level
     * @param       string      $message        $message
     * @param       array       $context        $context
     * @return      null
     */
    public function log($level, $message, array $context = array())
    {
        // verify given log level
        if (!$this->verifyLevel($level)) {
            throw new InvalidArgumentException('Give log level is not valid.');
        }

        // verify given log message
        if (!$this->verifyMessage($message)) {
            throw new InvalidArgumentException('log message can not be converted to string.');
        } else {
            if (is_object($message)) {
                $message = $message->__toString();
            }
        }

        // trigger registered log handlers
        foreach ($this->getHandlers() as $logHandler) {
            $logHandler->log($level, $message, $context);
        }
    }

    /**
     * @param       mixed       $level
     * @return      bool
     */
    public function verifyLevel($level)
    {
        if (is_string($level)
            && in_array($level, $this->logLevels)
        ) {
            return true;
        }
        return false;
    }

    /**
     * @param       string      $message
     * @return      bool
     */
    public function verifyMessage($message)
    {
        if (is_object($message)) {
            if (!method_exists($message, '__toString')) {
                return false;
            }
        } elseif (is_resource($message)) {
            return false;
        } elseif (is_array($message)) {
            return false;
        }
        return true;
    }
}