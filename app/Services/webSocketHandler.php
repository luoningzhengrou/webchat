<?php


namespace App\Services;


use Hhxsv5\LaravelS\Swoole\WebSocketHandlerInterface;
use Illuminate\Support\Facades\Log;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class webSocketHandler implements WebSocketHandlerInterface
{
    public function __construct()
    {
    }

    public function onOpen(Server $server, Request $request)
    {
        Log::info('WebSocket 连接建立：' . $request->fd);
    }

    public function onMessage(Server $server, Frame $frame)
    {
        Log::info("从 {$frame->fd} 接收到数据: {$frame->data}");
        foreach ($server->connections as $fd){
            if (!$server->isEstablished($fd)){
                //如果连接不可用则忽略
                continue;
            }
            $server->push($fd, $frame->data);  //服务端通过 push 方法向所有客户端广播消息
        }
    }

    public function onClose(Server $server, $fd, $reactorId)
    {
        Log::info('WebSocket 连接关闭:' . $fd);
    }

}
