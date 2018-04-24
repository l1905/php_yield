<?php 
    
    include "task_02.php";
    include "scheduler_05.php";
    include "systemcall.php";

    function newTask(Generator $coroutine) {
        return new SystemCall(
            function(Task $task, Scheduler $scheduler) use ($coroutine) {
                $task->setSendValue($scheduler->newTask($coroutine));
                $scheduler->schedule($task);
            }
        );
    }
    
    function waitForRead($socket) {
        return new SystemCall(
            function(Task $task, Scheduler $scheduler) use ($socket) {
                $scheduler->waitForRead($socket, $task);
            }
        );
    }
     
    function waitForWrite($socket) {
        return new SystemCall(
            function(Task $task, Scheduler $scheduler) use ($socket) {
                $scheduler->waitForWrite($socket, $task);
            }
        );
    }


    function server($port) {
        echo "Starting server at port $port...\n";
     
        $socket = @stream_socket_server("tcp://localhost:$port", $errNo, $errStr);
        if (!$socket) throw new Exception($errStr, $errNo);
     
        stream_set_blocking($socket, 0);
     
        while (true) {
            // echo time().PHP_EOL;
            yield waitForRead($socket);
            // echo "22222222".PHP_EOL;
            $clientSocket = stream_socket_accept($socket, 0);
            // echo "333333".PHP_EOL;
            yield newTask(handleClient($clientSocket));
        }
    }
     
    function handleClient($socket) {
        yield waitForRead($socket);
        $data = fread($socket, 8192);
     
        $msg = "Received following request:\n\n$data";
        $msgLength = strlen($msg);
     
        $response = <<<RES
HTTP/1.1 200 OK\r
Content-Type: text/plain\r
Content-Length: $msgLength\r
Connection: close\r
\r
$msg
RES;
     
        yield waitForWrite($socket);
        fwrite($socket, $response);
     
        fclose($socket);
    }
    
    /**
     * 一个简单的web服务
     * @DateTime 2018-04-24
     * @return   [type]           [description]
     */
    function main() {
        $scheduler = new Scheduler;
        $scheduler->newTask(server(8000));
        $scheduler->run();
    }

    main();