<?php
class Process{
    public $workers = [];
    public $pid;
    public $model;
    private function __clone(){//禁用克隆模式
        // TODO: Implement __clone() method.
    }
    public function create($func,$model='empty_null'){
        $this->model = $model;
        if($this->model==='pool'){
            for($i=0;$i<PROCESS_POOL;$i++){
                $process = new swoole_process(function($process)use($func){
                    $data = $func($process);
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
        }elseif($this->model==='single'){
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
        }else{
            exit("请输入多进程创建模式参数 @param string 'single'或'pool'");
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
        if($this->model===1){
            for($i=0;$i<PROCESS_POOL;$i++){
                unset($this->model);
                Swoole\Event::wait(); 
                unset($this->workers[$this->pid]);
            }
        }else{
            unset($this->model);
            Swoole\Event::wait();
        }
    }
}


    
    
