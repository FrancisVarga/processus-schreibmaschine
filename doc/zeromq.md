ZeroMQ Handler
==================

**ZeroMQ in 100 words:**
ØMQ (also seen as ZeroMQ, 0MQ, zmq) looks like an embeddable networking library but acts like a concurrency framework. It gives you sockets that carry atomic messages across various transports like in-process, inter-process, TCP, and multicast. You can connect sockets N-to-N with patterns like fanout, pub-sub, task distribution, and request-reply. It's fast enough to be the fabric for clustered products. Its asynchronous I/O model gives you scalable multicore applications, built as asynchronous message-processing tasks. It has a score of language APIs and runs on most operating systems. ØMQ is from iMatix and is LGPLv3 open source.

Usage:
----------

Server Script:
```php
<?php

$context = new ZMQContext();
$socket  = new ZMQSocket($context, ZMQ::SOCKET_PULL);
$socket->bind("tcp://*:5555");
$totalMessage = 0;
while (true) {
		
    usleep(500);

    try {

        $message = $socket->recv(ZMQ::MODE_NOBLOCK);

        if (empty($message)) {
            continue; 									// I love you PHP :D
        }
				
				// Your Code (Example)
        echo $totalMessage++ . PHP_EOL;
        echo $message . PHP_EOL;
				// Your Code (Example)

    } catch (\Exception $error) {
        var_dump($error);							// Logging would help to know what happen here :)
    }
}
```

Logging Example:
```php
<?php

require __DIR__ . "/../../vendor/autoload.php";

$connection = new \ZMQSocket(new \ZMQContext(), \ZMQ::SOCKET_PUSH, "monolog");
$connection->connect("tcp://127.0.0.1:5555");

$logger = new \Monolog\Logger("zeromq");
$logger->pushHandler(new \Monolog\Handler\ZeroMQHandler($connection));

$logger->err(
    "1232332",
    array(
        "id"     => 1,
        "params" => array(
            array(
                "message"  => "This is bullshit to.",
                "created"  => time(),
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
    )
);
```

In this scenario i'm using PUSH -> PULL socket.