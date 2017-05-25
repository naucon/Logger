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
use Naucon\Logger\LoggerInterface;
use Naucon\Logger\Handler\LogHandler;
use Naucon\Logger\Handler\StdHandler;
use Psr\Log\LogLevel;
use Psr\Log\Test\DummyTest;

class StdHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var         LogHandler
     */
    private $logHandler = null;



    /**
     * @return      void
     */
    static public function setUpBeforeClass()
    {
        // remove directories
        $logPath =  __DIR__ . '/log/test2.log';
        if (is_file($logPath)) {
            unlink($logPath);
        }
    }

    /**
     * @return      LoggerInterface
     */
    public function getLogger()
    {
        $logPath = __DIR__.'/log/test2.log';
        $this->logHandler = new StdHandler($logPath);

        $loggerObject = new Logger();
        $loggerObject->addHandler($this->logHandler);

        return $loggerObject;
    }

    public function testImplements()
    {
        $this->assertInstanceOf('Psr\Log\LoggerInterface', $this->getLogger());
    }

    /**
     * @dataProvider provideLevelsAndMessages
     * @param   string      $level
     * @param   string      $message
     */
    public function testLogsAtAllLevels($level, $message)
    {
        $logger = $this->getLogger();
        $logger->{$level}($message, array('user' => 'Bob'));
        $logger->log($level, $message, array('user' => 'Bob'));

//        $expected = array(
//            $level.' message of level '.$level.' with context: Bob',
//            $level.' message of level '.$level.' with context: Bob',
//        );
//        $this->assertEquals($expected, $this->getLogs());
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
     * @expectedException \Psr\Log\InvalidArgumentException
     */
    public function testThrowsOnInvalidLevel()
    {
        $logger = $this->getLogger();
        $logger->log('invalid level', 'Foo');
    }

    public function testContextReplacement()
    {
        $logger = $this->getLogger();
        $logger->info('{Message {nothing} {user} {foo.bar} a}', array('user' => 'Bob', 'foo.bar' => 'Bar'));

//        $expected = array('info {Message {nothing} Bob Bar a}');
//        $this->assertEquals($expected, $this->getLogs());
    }

    public function testObjectCastToString()
    {
        $dummy = $this->getMock('Psr\Log\Test\DummyTest', array('__toString'));
        $dummy->expects($this->once())
            ->method('__toString')
            ->will($this->returnValue('DUMMY'));

        $this->getLogger()->warning($dummy);
    }

    public function testContextCanContainAnything()
    {
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

        $this->getLogger()->warning('Crazy context data', $context);
    }

    public function testContextExceptionKeyCanBeExceptionOrOtherValues()
    {
        $this->getLogger()->warning('Random message', array('exception' => 'oops'));
        $this->getLogger()->critical('Uncaught Exception!', array('exception' => new \LogicException('Fail')));
    }
}