<?php

use OpenSwoole\WebSocket\Server;
use OpenSwoole\Http\Request;
use OpenSwoole\WebSocket\Frame;

$server = new Server("0.0.0.0", 8080);

$count = 1;


$server->on("Start", function(Server $server)
{
    echo "OpenSwoole WebSocket Server is started at http://127.0.0.1:8080\n";
});

$server->on('Open', function(Server $server, OpenSwoole\Http\Request $request) use(&$count)
{
	$count = $count + 1;
	echo "connection open: {$request->fd}\n";
	echo "test: {$count}";
	echo OpenSwoole\Coroutine::getCid();

});

$server->on('Message', function(Server $server, Frame $frame)
{
    echo "received message: {$frame->data}\n";
    $server->push($frame->fd, json_encode(["hello", time()]));
});

$server->on('Close', function(Server $server, int $fd)
{
    echo "connection close: {$fd}\n";
});

$server->on('Disconnect', function(Server $server, int $fd)
{
    echo "connection disconnect: {$fd}\n";
});

$server->start();
