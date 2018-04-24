<?php 
    
    include "task_02.php";
    include "scheduler_03.php";
    include "systemcall.php";

    function getTaskId() {
        return new SystemCall(function(Task $task, Scheduler $scheduler) {
            $task->setSendValue($task->getTaskId());
            $scheduler->schedule($task);
        });
    }

    function task($max) {
        $tid = (yield getTaskId()); // <-- here's the syscall!
        for ($i = 1; $i <= $max; ++$i) {
            echo "This is task $tid iteration $i.\n";
            yield;
        }
    }

    /**
     * 任务调度相关
     * @DateTime 2018-04-23
     * @return   [type]           [description]
     */
    function main()
    {
        $scheduler = new Scheduler;
         
        $scheduler->newTask(task(10));
        $scheduler->newTask(task(5));
         
        $scheduler->run();
    }

    main();

?>