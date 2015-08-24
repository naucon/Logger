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
     * @param       mixed                   log level
     * @param       string                  log message
     * @param       array                   log context
     */
    public function __construct($level, $message, array $context=array())
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
     * @param       sting                   log level
     * @return      void
     */
    public function setLevel($level)
    {
        $this->level = (string)$level;
    }

    /**
     * @param       bool                    raw message
     * @return      string                  log message
     */
    public function getMessage($raw=false)
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
     * @param       string                  log message
     * @return      void
     */
    public function setMessage($message)
    {
        $this->message = (string)$message;
    }

    /**
     * @return      array                   log context
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param       array                  log context
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
     * @param       float                   unix timestamp with microseconds
     * @return      void
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }
}