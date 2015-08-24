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
use Naucon\Logger\LogLevel;
use Naucon\Logger\Handler\HandlerAbstract;
use Naucon\Logger\Handler\Exception\HandlerException;
use Gelf\PublisherInterface;
use Gelf\Message;

/**
 * Gelf Logger Class
 * GrayLog Format
 *
 * @package    Logger
 * @subpackage Handler
 * @author     Sven Sanzenbacher
 */
class GelfHandler extends HandlerAbstract
{
    /**
     * @access      protected
     * @var         PublisherInterface
     */
    protected $publisher;

    /**
     * @access	    protected
     * @var	        string
     */
    protected $host;

    /**
     * @access	    protected
     * @var         mixed
     */
    protected $facility;

    /**
     * Constructor
     *
     * @param       PublisherInterface
     * @param       string                  host e.g. domain
     * @param       mixed                   facitly e.g. component
     * @param       string                  log level
     *
     * facility can be any string or an integer according to syslog facility
     * numbers (You can set and overwrite facility names in the web interface)
     *  0 = kernel messages
     *  1 = user-level messages
     *  2 = mail system
     *  3 = system daemons
     *  4 = security/authorization messages
     *  5 = messages generated internally by syslogd
     *  6 = line printer subsystem
     *  7 = network news subsystem
     *  8 = UUCP subsystem
     *  9 = clock daemon
     * 10 = security/authorization messages
     * 11 = FTP daemon
     * 12 = NTP subsystem
     * 13 = log audit
     * 14 = log alert
     * 15 = clock daemon
     * 16 = local0
     * 17 = local1
     * 18 = local2
     * 19 = local3
     * 20 = local4
     * 21 = local5
     * 22 = local6
     * 23 = local7
     */
    public function __construct(PublisherInterface $publisher, $host, $facility, $level=LogLevel::DEBUG)
    {
        $this->setPublisher($publisher);
        $this->setHost($host);
        $this->setFacility($facility);

        parent::__construct($level);
    }


    /**
     * @return	PublisherInterface
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * @param	PublisherInterface
     * @return  void
     */
    public function setPublisher(PublisherInterface $publisher)
    {
        return $this->publisher = $publisher;
    }

    /**
     * @return	string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param	string
     * @return  void
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @return	mixed                   facitly e.g. component
     */
    public function getFacility()
    {
        return $this->facility;
    }

    /**
     * @param	mixed                   facitly e.g. component
     * @return  void
     *
     * facility can be any string or an integer according to syslog facility
     * numbers (You can set and overwrite facility names in the web interface)
     *  0 = kernel messages
     *  1 = user-level messages
     *  2 = mail system
     *  3 = system daemons
     *  4 = security/authorization messages
     *  5 = messages generated internally by syslogd
     *  6 = line printer subsystem
     *  7 = network news subsystem
     *  8 = UUCP subsystem
     *  9 = clock daemon
     * 10 = security/authorization messages
     * 11 = FTP daemon
     * 12 = NTP subsystem
     * 13 = log audit
     * 14 = log alert
     * 15 = clock daemon
     * 16 = local0
     * 17 = local1
     * 18 = local2
     * 19 = local3
     * 20 = local4
     * 21 = local5
     * 22 = local6
     * 23 = local7
     */
    public function setFacility($facility)
    {
        $this->facility = $facility;
    }

    /**
     * @access      protected
     * @param       LogRecord
     * @return      void
     */
    protected function processRecord(\Naucon\Logger\LogRecord $logRecord)
    {
        $message = new Message();
        $message->setLevel($logRecord->getLevel());
        $message->setShortMessage($logRecord->getMessage());
        $message->setHost($this->getHost());
        $message->setFacility($this->getFacility());

        if (method_exists($logRecord, 'getCreated()')) {
            $message->setTimestamp($logRecord->getCreated());
        } else {
            $message->setTimestamp(time(true));
        }

        $context = $logRecord->getContext();
        if (isset($context['exception'])
            && $context['exception'] instanceof \Exception) {

            /**
             * @var \Exception $exception
             */
            $exception = $context['exception'];
            $message->setFullMessage($exception->getTraceAsString());
            $message->setFile($exception->getFile());
            $message->setLine($exception->getLine());
        }

        // $message->setAdditional("Additional Field 1", "bla bla");
        // $message->setAdditional("Additional Field 2", "lirum larum");

        $this->getPublisher()->publish($message);
    }
}