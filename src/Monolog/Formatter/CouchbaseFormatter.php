<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hissterkiller
 * Date: 11/26/12
 * Time: 2:34 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Monolog\Formatter;

use Monolog\Handler\GroupHandler;

class CouchbaseFormatter implements FormatterInterface
{

    /**
     * Formats a log record.
     *
     * @param  array $record A record to format
     *
     * @return mixed The formatted record
     */
    public function format(array $record)
    {
        $key = implode(
            ":",
            array(
                strtolower($record['channel']),
                strtolower($record['level_name']),
                md5(uniqid(null, true))
            )
        );

        $record["meta"] = array(
            "key"        => $key,
            "expireTime" => $record['context']['expireTime']
        );
        $logData        = array(
            "message" => $record['message'],
            "context" => $record['context'],
            "extra"   => $record['extra']
        );

        $record['value'] = $logData;

        return $record;
    }

    /**
     * Formats a set of log records.
     *
     * @param  array $records A set of records to format
     *
     * @return mixed The formatted set of records
     */
    public function formatBatch(array $records)
    {
        $formatted = array();

        foreach ($records as $record) {
            $formatted[] = $this->format($record);
        }

        return $formatted;
    }
}
