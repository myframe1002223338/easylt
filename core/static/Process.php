<?php
class Process{
    public $workers = [];
    public $pid;
    private function __clone(){//禁用克隆模式
        // TODO: Implement __clone() method.
    }
    public function create($func){
        if(PROCESS_POOL[0]===1){
            for($i=0;$i<PROCESS_POOL[1];$i++){
                $process = new swoole_process(function($process)use($func){
                    $data = $func();
                    if($data!=null){
                        $process->write($data);
                    }
                });
                if(PROCESS_DAEMONIZE===1){
                    swoole_process::daemon();
                }
                $this->pid = $process->start();
                $this->workers[$this->pid] = $process;
            }
        }else{
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
        }
    }
    public function pipe($func){
        foreach($this->workers as $process){
            swoole_event_add($process,function($pipe)use($process,$func){
                $data = $process->read();
                $func($data);
                swoole_event_del($pipe);
            });
        }
    }
    public function __destruct(){
        if(PROCESS_POOL[0]===1){
            for($i=0;$i<PROCESS_POOL[1];$i++){
                Swoole\Event::wait(); 
                unset($this->workers[$this->pid]);
            }
        }else{
            Swoole\Event::wait();
        }
    }
}


    
    
