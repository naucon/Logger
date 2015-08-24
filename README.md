# naucon Logger Package

## About

This package contains a simple logging class for php that handle one or more PSR-3 compatible loggers.

### Features

* logger
    * handle one or more loggers
    * log level
    	* EMERGENCY
    	* ALERT
    	* CRITICAL
    	* ERROR
    	* WARNING
    	* NOTICE
    	* INFO
    	* DEBUG
    * messages
        * with context
        * pass exception as message
    * PSR-3 compatible
* handler
    * null handler
        * log nothing
 	* standard handler (StdHandler)
 	    * log file
 	    * restrict logging to a given log level
 	    * exception stack trace
 	* standard error handler (StderrHandler)
 	    * write into php error log
 	    * restrict logging to a given log level
 	    * exception stack trace
	* log handler (LogHandler)
	    * log file
	    * restrict logging to a given log level
	    * contain duration
	    * contain IP-Address (current user IP)
	    * contain User ID (set from outside)
	    * exception stack trace
	* gelf handler
	    * push messages to GrayLog2 server
* singleton class (application wide)


### Compatibility

* PHP5.3


## Installation

install the latest version via composer 

    composer require naucon/logger

## Basic Usage

### Logger (with StdHandler)

To implement the logger create a instance of `Logger`:

	use Naucon\Logger\Logger;
	$loggerObject = new Logger();

Add a handler to the logger to define where the log messages are written. In our example we use the `StdHandler` to log the message to a file.

    $logPath = __DIR__ . '/log/test.log';

    use Naucon\Logger\Handler\StdHandler;
    $logHandler = new StdHandler($logPath);

    $loggerObject->addHandler($logHandler);

The Handler can also be restricted to a certain log level.

    use Naucon\Logger\LogLevel;
    use Naucon\Logger\Handler\StdHandler;
    $logHandler = new StdHandler(LogLevel::WARNING);

The logger class provides the following methods to log messages:

* emergency($message, array $context=array());
* alert($message, array $context=array());
* critical($message, array $context=array());
* error($message, array $context=array());
* warning($message, array $context=array());
* notice($message, array $context=array());
* info($message, array $context=array());
* debug($message, array $context=array());

The log message is passed through `$message`. Context data can be added with `$context`.

    $loggerObject->emergency('message of level emergency');
    $loggerObject->alert('message of level alert');
    $loggerObject->critical('message of level critical');
    $loggerObject->error('message of level error');
    $loggerObject->warning('message of level warning');
    $loggerObject->notice('message of level notice');
    $loggerObject->info('message of level info');
    $loggerObject->debug('message of level debug');

The log file would look like this:

    [01-Jul-2012 15:54:25 Europe/Paris] PHP emergency: message of level emergency with context: Bob
    [01-Jul-2012 15:54:25 Europe/Paris] PHP alert: message of level alert with context: Bob
    [01-Jul-2012 15:54:25 Europe/Paris] PHP critical: message of level critical with context: Bob
    [01-Jul-2012 15:54:25 Europe/Paris] PHP alert: message of level alert with context: Bob
    [01-Jul-2012 15:54:25 Europe/Paris] PHP error: message of level error with context: Bob
    [01-Jul-2012 15:54:25 Europe/Paris] PHP warning: message of level warning with context: Bob
    [01-Jul-2012 15:54:25 Europe/Paris] PHP notice: message of level notice with context: Bob
    [01-Jul-2012 15:54:25 Europe/Paris] PHP info: message of level info with context: Bob
    [01-Jul-2012 15:54:25 Europe/Paris] PHP debug: message of level debug with context: Bob

The log message can contain placeholders `{user}` which can be replaced with values form the `$context`.

    $loggerObject->emergency('message of level emergency with context: {user}', array('user' => 'Bob'));

A object can also be passed as message as long the object implements `__toString()` method.

    $loggerObject->critical(new \Exception('invalid foo'));

Logging exceptions is a common pattern a it allows to log a stack trace. Therefor a instance of `Exception` must be passed as `$context` with the key `exception`.

    $exception = new \Exception('invalid foo');
    $loggerObject->error($exception->getMessage(), array('exception' => $exception));

### Standard Error Handler (StderrHandler)

The logger package contains a Standard Error Handler to write into the php error log.

To use the Handler create a instance of the `StderrHandler` and add the handler to the `Logger`.

    use Naucon\Logger\Handler\StderrHandler;
    $logHandler = new StderrHandler();

The Handler can also be restricted to a certain log level.

    use Naucon\Logger\LogLevel;
    use Naucon\Logger\Handler\StderrHandler;
    $logHandler = new StderrHandler(LogLevel::WARNING);

The log file would look like this:

    [01-Jul-2012 15:54:25 Europe/Paris] PHP emergency: message of level emergency with context: Bob
    [01-Jul-2012 15:54:25 Europe/Paris] PHP alert: message of level alert with context: Bob
    [01-Jul-2012 15:54:25 Europe/Paris] PHP critical: message of level critical with context: Bob
    [01-Jul-2012 15:54:25 Europe/Paris] PHP alert: message of level alert with context: Bob
    [01-Jul-2012 15:54:25 Europe/Paris] PHP error: message of level error with context: Bob
    [01-Jul-2012 15:54:25 Europe/Paris] PHP warning: message of level warning with context: Bob
    [01-Jul-2012 15:54:25 Europe/Paris] PHP notice: message of level notice with context: Bob
    [01-Jul-2012 15:54:25 Europe/Paris] PHP info: message of level info with context: Bob
    [01-Jul-2012 15:54:25 Europe/Paris] PHP debug: message of level debug with context: Bob

#### Log Handler (LogHandler)

The logger package contains a Handler with another log format with additional data:

	    * duration
	    * IP-Address (current user IP)
	    * User ID (set from outside)

To use the Handler create a instance of the `LogHandler` and add the handler to the `Logger`.

    $logPath = __DIR__ . '/log/test.log';

    use Naucon\Logger\Handler\LogHandler;
    $logHandler = new LogHandler($logPath);

The Handler can also be restricted to a certain log level.

    use Naucon\Logger\LogLevel;
    use Naucon\Logger\Handler\LogHandler;
    $logHandler = new LogHandler($logPath, LogLevel::WARNING);

The log file would look like this:

	2010-02-07 19:28:43  0           EMERGENCY [127.0.0.1]         unkown     message of level emergency
	2010-02-07 19:28:43  0.001       ALERT     [127.0.0.1]         unkown     message of level alert
	2011-10-08 22:26:09  0.001       CRITICA   [127.0.0.1]         unkown     message of level critical
	2011-10-08 22:26:09  0.001       ERROR     [127.0.0.1]         unkown     message of level error
	2011-10-08 22:26:09  0.001       WARNING   [127.0.0.1]         unkown     message of level warning
	2011-10-08 22:26:09  0.001       NOTICE    [127.0.0.1]         unkown     message of level notice
	2011-10-08 22:26:09  0.001       INFO      [127.0.0.1]         unkown     message of level info
	2011-10-08 22:26:09  0.001       DEBUG     [127.0.0.1]         unkown     message of level debug

### Gelf Handler

The logger package contains a Handler to push GELF message to a GrayLog2 Server.

First add the `graylog2/gelf-php` package to your `composer.json`.

    "require": {
        "graylog2/gelf-php" : "~1.0"
    }

Then create a Gelf Transporter and Gelf Publisher.

    $transport = new Gelf\Transport\UdpTransport("127.0.0.1", 12201, Gelf\Transport\UdpTransport::CHUNK_SIZE_LAN);
    $publisher = new Gelf\Publisher($transport);

Afterwards create a instance of the `GelfHandler` and add the handler to the `Logger`.

    use Naucon\Logger\Handler\GelfHandler;
    $logHandler = new GelfHandler($publisher, 'www.nclib.dev', 'example-facility');

The GelfHandle can also be restricted to a certain log level.

    use Naucon\Logger\LogLevel;
    use Naucon\Logger\Handler\GelfHandler;
    $logHandler = new GelfHandler($publisher, 'www.nclib.dev', 'example-facility', LogLevel::WARNING);

Since version 1.0 the `graylog2/gelf-php` package comes with its own logger. This PSR-3 compatible Logger can added as handler our `Logger`.

    use Naucon\Logger\Logger;
    $loggerObject = new Logger();
    $loggerObject->addHandler(new Gelf\Logger());

    $loggerObject->emergency('message of level emergency');


### Logger Manager

The locale package contains the class `LoggerManager` with a singleton pattern to make a instance of `Logger` accessible from anywhere inside of your application.

Instead of the singleton class you can also use a service container.

Create a instance of `LoggerManager` by calling `init()` statically. Attach a instance of `Logger` to the method `init()` as parameter.

    $logPath = __DIR__ . '/log/test2.log';

    use Naucon\Logger\Logger;
    $loggerObject = new Logger();

    use Naucon\Logger\Handler\LogHandler;
    $logHandler = new LogHandler($logPath);
    $loggerObject->addHandler($logHandler);

    use Naucon\Logger\LoggerManager;
    LoggerManager::init($loggerObject);

To write into the system log file, we call the known methods statically:

    LoggerManager::emergency('message of level emergency');
    LoggerManager::alert('message of level alert');
    LoggerManager::critical('message of level critical');
    LoggerManager::alert('message of level alert');
    LoggerManager::error('message of level error');
    LoggerManager::warning('message of level warning');
    LoggerManager::notice('message of level notice');
    LoggerManager::info('message of level info');
    LoggerManager::debug('message of level debug');


## License

The MIT License (MIT)

Copyright (c) 2015 Sven Sanzenbacher

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
