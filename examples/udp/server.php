<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hissterkiller
 * Date: 11/15/12
 * Time: 9:33 PM
 * To change this template use File | Settings | File Templates.
 */

$socket = stream_socket_server("udp://127.0.0.1:1113", $errno, $errstr, STREAM_SERVER_BIND);
if (!$socket) {
    die("$errstr ($errno)");
}

$totalMessage = 0;
do {
    $pkt = stream_socket_recvfrom($socket, 512, 0, $peer);
    $totalMessage++;
    echo $totalMessage . PHP_EOL;
    echo $pkt . PHP_EOL;

} while ($pkt !== false);