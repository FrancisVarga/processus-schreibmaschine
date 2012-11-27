<?php

namespace Monolog;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;

class Tracking
{

    /**
     * @var DateTimeZone
     */
    protected static $timezone;

    /**
     * The handler stack
     *
     * @var array of Monolog\Handler\HandlerInterface
     */
    protected $handlers = array();

    /**
     * @param string $name The logging channel
     */
    public function __construct($name)
    {
        $this->name = $name;

        if (!static::$timezone) {
            static::$timezone = new \DateTimeZone(date_default_timezone_get() ? : 'UTC');
        }
    }

    /**
     * @param $event
     * @param $mesage
     * @param $context
     */
    public function trackEvent($event, $mesage, $context)
    {
        $this->addRecord($mesage, $context, $event);
    }

    /**
     * Adds a log record.
     *
     * @param  integer $level   The logging level
     * @param  string  $message The log message
     * @param  array   $context The log context
     *
     * @return Boolean Whether the record has been processed
     */
    public function addRecord($message, array $context = array(), $level = Logger::DEBUG)
    {
        if (!$this->handlers) {
            $this->pushHandler(new StreamHandler('php://stderr', 100));
        }
        $record = array(
            'message'    => (string)$message,
            'context'    => $context,
            'level'      => Logger::DEBUG, // hardcoded to passed in HandlerInterface::isHandling function
            'level_name' => 'tracking',
            'channel'    => $this->name,
            'datetime'   => \DateTime::createFromFormat(
                'U.u',
                sprintf('%.6F', microtime(true)),
                static::$timezone
            )->setTimezone(static::$timezone),
            'extra'      => array(),
        );

        /** @var $handler HandlerInterface */
        foreach ($this->handlers as $key => $handler) {
            if ($handler->isHandling($record)) {
                $handlerKey = $key;
                break;
            }
        }

        while (isset($this->handlers[$handlerKey]) &&
            false === $this->handlers[$handlerKey]->handle($record)) {
            $handlerKey++;
        }

        return true;
    }

    /**
     * Pushes a handler on to the stack.
     *
     * @param HandlerInterface $handler
     */
    public function pushHandler(HandlerInterface $handler)
    {
        array_unshift($this->handlers, $handler);
    }

}
