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

/**
 * Log Record Class
 *
 * @package    Logger
 * @author     Sven Sanzenbacher
 */
class LogRecord
{
    /**
     * @var         string
     */
    protected $level = null;

    /**
     * @var         string
     */
    protected $message = null;

    /**
     * @var         array
     */
    protected $context = array();

    /**
     * @var         float                   unix timestamp with microseconds
     */
    protected $created = null;



    /**
     * Constructor
     *
     * @param       mixed       $level          log level
     * @param       string      $message        log message
     * @param       array       $context        log context
     */
    public function __construct($level, $message, array $context = array())
    {
        $this->setLevel($level);
        $this->setMessage($message);
        $this->setContext($context);

        $this->setCreated(microtime(true));
    }



    /**
     * @return      string                  log level
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param       string      $level      log level
     * @return      void
     */
    public function setLevel($level)
    {
        $this->level = (string)$level;
    }

    /**
     * @param       bool        $raw        raw message
     * @return      string                  log message
     */
    public function getMessage($raw = false)
    {
        $message = (string)$this->message;
        if (!$raw) {
            $context = $this->getContext();
            if (strpos($message, '{')!==false) {
                $replace = array();
                foreach ($context as $key => $value) {
                    $replace['{'.(string)$key.'}'] = (string)$value;
                }

                $message = strtr((string)$message, $replace);
            }
        }
        return $message;
    }

    /**
     * @param       string      $message        log message
     * @return      void
     */
    public function setMessage($message)
    {
        $this->message = (string)$message;
    }

    /**
     * @return      array                       log context
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param       array       $context        log context
     * @return      void
     */
    public function setContext(array $context)
    {
        $this->context = $context;
    }

    /**
     * @return      float                   unix timestamp with microseconds
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param       float       $created        unix timestamp with microseconds
     * @return      void
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }
}