<?php
/*
 * Copyright 2015 Sven Sanzenbacher
 *
 * This file is part of the naucon package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Naucon\Logger\Tests;

use Naucon\Logger\Logger;
use Naucon\Logger\LogRecord;
use Naucon\Logger\LoggerManager;
use Naucon\Logger\Tests\Handler\LogHandler;
use Psr\Log\LogLevel;
use Psr\Log\Test\DummyTest;

class LoggerManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var         LogHandler
     */
    private $logHandler = null;

    /**
     * @return      void
     */
    public function initLogger()
    {
        $this->logHandler = new LogHandler();

        $loggerObject = new Logger();
        $loggerObject->addHandler($this->logHandler);

        LoggerManager::init($loggerObject);
	}

    /**
     * This must return the log messages in order with a simple formatting: "<LOG LEVEL> <MESSAGE>"
     *
     * Example ->error('Foo') would yield "error Foo"
     *
     * @return      string
     */
    public function getLogs()
    {
        $logs = array();
        $logRecords = $this->logHandler->getRecords();
        foreach ($logRecords as $logRecord) {
            /**
             * @var LogRecord   $logRecord
             */
            $logs[] = $logRecord->getLevel() . ' ' . $logRecord->getMessage();
        }
        return $logs;
    }

    /**
     * @dataProvider provideLevelsAndMessages
     */
    public function testLogsAtAllLevels($level, $message)
    {
        $this->initLogger();

        LoggerManager::$level($message, array('user' => 'Bob'));
        LoggerManager::log($level, $message, array('user' => 'Bob'));

        $expected = array(
            $level.' message of level '.$level.' with context: Bob',
            $level.' message of level '.$level.' with context: Bob',
        );
        $this->assertEquals($expected, $this->getLogs());
    }

    public function provideLevelsAndMessages()
    {
        return array(
            LogLevel::EMERGENCY => array(LogLevel::EMERGENCY, 'message of level emergency with context: {user}'),
            LogLevel::ALERT => array(LogLevel::ALERT, 'message of level alert with context: {user}'),
            LogLevel::CRITICAL => array(LogLevel::CRITICAL, 'message of level critical with context: {user}'),
            LogLevel::ERROR => array(LogLevel::ERROR, 'message of level error with context: {user}'),
            LogLevel::WARNING => array(LogLevel::WARNING, 'message of level warning with context: {user}'),
            LogLevel::NOTICE => array(LogLevel::NOTICE, 'message of level notice with context: {user}'),
            LogLevel::INFO => array(LogLevel::INFO, 'message of level info with context: {user}'),
            LogLevel::DEBUG => array(LogLevel::DEBUG, 'message of level debug with context: {user}'),
        );
    }

    /**
     * @expectedException Psr\Log\InvalidArgumentException
     */
    public function testThrowsOnInvalidLevel()
    {
        $this->initLogger();

        LoggerManager::log('invalid level', 'Foo');
    }

    public function testContextReplacement()
    {
        $this->initLogger();

        LoggerManager::info('{Message {nothing} {user} {foo.bar} a}', array('user' => 'Bob', 'foo.bar' => 'Bar'));

        $expected = array('info {Message {nothing} Bob Bar a}');
        $this->assertEquals($expected, $this->getLogs());
    }

    public function testObjectCastToString()
    {
        $this->initLogger();

        $dummy = $this->getMock('Psr\Log\Test\DummyTest', array('__toString'));
        $dummy->expects($this->once())
            ->method('__toString')
            ->will($this->returnValue('DUMMY'));

        LoggerManager::warning($dummy);
    }

    public function testContextCanContainAnything()
    {
        $this->initLogger();

        $context = array(
            'bool' => true,
            'null' => null,
            'string' => 'Foo',
            'int' => 0,
            'float' => 0.5,
            'nested' => array('with object' => new DummyTest),
            'object' => new \DateTime,
            'resource' => fopen('php://memory', 'r'),
        );

        LoggerManager::warning('Crazy context data', $context);
    }

    public function testContextExceptionKeyCanBeExceptionOrOtherValues()
    {
        $this->initLogger();

        LoggerManager::warning('Random message', array('exception' => 'oops'));
        LoggerManager::critical('Uncaught Exception!', array('exception' => new \LogicException('Fail')));
    }
}