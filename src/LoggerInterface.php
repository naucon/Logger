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
 * Logger Interface
 *
 * @abstract
 * @package    Logger
 * @author      Sven Sanzenbacher
 */
interface LoggerInterface extends PsrLoggerInterface
{
    /**
     * register a log handle to handle log messages
     *
     * @param       Psr\Log\LoggerInterface
     * @return      void
     */
    public function addHandler(PsrLoggerInterface $logHandlerObject);

    /**
     * return the registered log handlers
     *
     * @return      array
     */
    public function getHandlers();
}