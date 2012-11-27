<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hissterkiller
 * Date: 11/26/12
 * Time: 1:00 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Monolog\Handler;

use Monolog\Formatter\JsonFormatter;
use Processus\Ruhebett\Interfaces\NoSQLInterface;
use Processus\Ruhebett\Memcached\ClientJson;
use Monolog\Logger;
use Monolog\Formatter\CouchbaseFormatter;

class CouchbaseHandler extends AbstractProcessingHandler
{

    /**
     * @var
     */
    private $client;

    /**
     * @param \Processus\Ruhebett\Interfaces\NoSQLInterface $client
     * @param bool|int                                      $level
     * @param bool                                          $bubble
     */
    public function __construct(NoSQLInterface $client = null, $level = Logger::DEBUG, $bubble = true)
    {
        if (empty($client)) {
            $this->client = new ClientJson();
            $this->client->setHost("127.0.0.1")
                ->setPort(11211)
                ->initClient();
        }

        parent::__construct($level, $bubble);
    }

    /**
     * @return \Monolog\Formatter\FormatterInterface|\Monolog\Formatter\JsonFormatter
     */
    protected function getDefaultFormatter()
    {
        return new CouchbaseFormatter();
    }

    /**
     * Writes the record down to the log of the implementing handler
     *
     * @param  array $record
     *
     * @return void
     */
    protected function write(array $record)
    {
        $data = $record['formatted'];
        $meta = $data['meta'];
        unset($data['meta']);

        $this->client->insert($meta['key'], $data, $meta['expireTime']);
    }

}
