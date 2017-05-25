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

/**
 * Null Logger Class
 *
 * @package    Logger
 * @subpackage Handler
 * @author     Sven Sanzenbacher
 */
class NullHandler extends HandlerAbstract
{
    /**
     * @access      protected
     * @param       LogRecord       $logRecord
     * @return      void
     */
    protected function processRecord(LogRecord $logRecord)
    {
        // noop
    }
}