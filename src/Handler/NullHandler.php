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
     * @param       LogRecord
     * @return      void
     */
    protected function processRecord(\Naucon\Logger\LogRecord $logRecord)
    {
        // noop
    }
}