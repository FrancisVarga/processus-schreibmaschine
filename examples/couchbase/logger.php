<?php
require __DIR__ . "/../../vendor/autoload.php";

use Monolog\Logger;
use Monolog\Handler\CouchbaseHandler;
use Monolog\Processor\MemoryPeakUsageProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\WebProcessor;

$error = array(
    "expireTime" => 0,
    "params"     => array(
        array(
            "message"  => "This is bullshit to.",
            "created"  => microtime(true),
            "someData" => mt_rand(0, 49344409875093475),
            "user"     => array(
                "firstname" => "Francis",
                "lastname"  => "Varga",
                "name"      => "Francis Varga",
                "address"   => "foobar street 1234567890 Berlin",
                "bio"       => "Awesome shit",
                "email"     => "foobar[at]barfoo.com",
            ),
        )
    )
);

$logger = new Logger("couchbase");
$logger->pushProcessor(new MemoryUsageProcessor());
$logger->pushProcessor(new MemoryPeakUsageProcessor());
$logger->pushProcessor(new IntrospectionProcessor());
$logger->pushProcessor(new WebProcessor());
$logger->pushHandler(new CouchbaseHandler());
$logger->err("error message", $error);