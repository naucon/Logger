<?php
$logPath = __DIR__ . '/log/test3.log';

use Naucon\Logger\Handler\StdHandler;
$logHandler = new StdHandler($logPath);

// restrict log level to warning and bellow
//use Naucon\Logger\LogLevel;
//use Naucon\Logger\Handler\StdHandler;
//$logHandler = new StdHandler($logPath, LogLevel::WARNING);

use Naucon\Logger\Logger;
$loggerObject = new Logger();
$loggerObject->addHandler($logHandler);

$loggerObject->emergency('message of level emergency');
$loggerObject->alert('message of level alert');
$loggerObject->critical('message of level critical');
$loggerObject->alert('message of level alert');
$loggerObject->error('message of level error');
$loggerObject->warning('message of level warning');
$loggerObject->notice('message of level notice');
$loggerObject->info('message of level info');
$loggerObject->debug('message of level debug');

$loggerObject->critical(new \Exception('invalid foo'));

// with context
$loggerObject->emergency('message of level emergency with context: {user}', array('user' => 'Bob'));
$loggerObject->alert('message of level alert with context: {user}', array('user' => 'Bob'));
$loggerObject->critical('message of level critical with context: {user}', array('user' => 'Bob'));
$loggerObject->alert('message of level alert with context: {user}', array('user' => 'Bob'));
$loggerObject->error('message of level error with context: {user}', array('user' => 'Bob'));
$loggerObject->warning('message of level warning with context: {user}', array('user' => 'Bob'));
$loggerObject->notice('message of level notice with context: {user}', array('user' => 'Bob'));
$loggerObject->info('message of level info with context: {user}', array('user' => 'Bob'));
$loggerObject->debug('message of level debug with context: {user}', array('user' => 'Bob'));


$exception = new \Exception('invalid foo');
$loggerObject->error($exception->getMessage(), array('exception' => $exception));
