<?php
$logPath = __DIR__ . '/log/test2.log';

use Naucon\Logger\Logger;
use Naucon\Logger\LoggerManager;
use Naucon\Logger\Handler\LogHandler;

$logHandler = new LogHandler($logPath);

$loggerObject = new Logger();
$loggerObject->addHandler($logHandler);

LoggerManager::init($loggerObject);

LoggerManager::emergency('message of level emergency');
LoggerManager::alert('message of level alert');
LoggerManager::critical('message of level critical');
LoggerManager::alert('message of level alert');
LoggerManager::error('message of level error');
LoggerManager::warning('message of level warning');
LoggerManager::notice('message of level notice');
LoggerManager::info('message of level info');
LoggerManager::debug('message of level debug');

LoggerManager::critical(new \Exception('invalid foo'));

// with context
LoggerManager::emergency('message of level emergency with context: {user}', array('user' => 'Bob'));
LoggerManager::alert('message of level alert with context: {user}', array('user' => 'Bob'));
LoggerManager::critical('message of level critical with context: {user}', array('user' => 'Bob'));
LoggerManager::alert('message of level alert with context: {user}', array('user' => 'Bob'));
LoggerManager::error('message of level error with context: {user}', array('user' => 'Bob'));
LoggerManager::warning('message of level warning with context: {user}', array('user' => 'Bob'));
LoggerManager::notice('message of level notice with context: {user}', array('user' => 'Bob'));
LoggerManager::info('message of level info with context: {user}', array('user' => 'Bob'));
LoggerManager::debug('message of level debug with context: {user}', array('user' => 'Bob'));

$exception = new \Exception('invalid foo');
LoggerManager::error($exception->getMessage(), array('exception' => $exception));