<?php
class Process{
    private function __clone(){//禁用克隆模式
        // TODO: Implement __clone() method.
    }
    public function create($func){
        $process = new swoole_process(function($process)use($func){
            $func($process);
        });
        if(PROCESS_QUEUE===1){
            $process->useQueue();
        }
        if(PROCESS_DAEMONIZE===1){
            swoole_process::daemon();
        }
        $process->start();
        Swoole\Event::wait();
    }
}