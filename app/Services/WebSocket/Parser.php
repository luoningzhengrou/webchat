<?php


namespace App\Services\WebSocket;


use Illuminate\Support\Facades\App;

abstract class Parser
{
    protected $strategies = [];

    public function execute($server, $frame)
    {
        $skip = false;
        foreach ($this->strategies as $strategy){
            $result = App::call(
                $strategy . '@handle',
                [
                    'server' => $server,
                    'frame' => $frame
                ]
            );
            if ($result === true){
                $skip = true;
                break;
            }
        }
        return $skip;
    }

    abstract public function encode(string $event, $data);

    abstract public function decode($frame);

}
