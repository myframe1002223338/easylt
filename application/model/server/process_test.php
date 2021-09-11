<?php
include('server.ini');
$process = new process;
$process->create(function($process){
    while(1){
        $send = mt_rand(1,9);
        $process->push($send);
        sleep(2);
    }
});
$process->create(function($process){
    while(1){
        $read = $process->pop();
        echo $read;
        sleep(3);
    }
});



