<?php 
    
    include "task_02.php";
    include "scheduler_04.php";
    include "systemcall.php";

    function getTaskId() {
        return new SystemCall(function(Task $task, Scheduler $scheduler) {
            $task->setSendValue($task->getTaskId());
            $scheduler->schedule($task);
        });
    }

    function newTask(Generator $coroutine) {
        return new SystemCall(
            function(Task $task, Scheduler $scheduler) use ($coroutine) {
                $task->setSendValue($scheduler->newTask($coroutine));
                $scheduler->schedule($task);
            }
        );
    }
     
    function killTask($tid) {
        return new SystemCall(
            function(Task $task, Scheduler $scheduler) use ($tid) {
                $task->setSendValue($scheduler->killTask($tid));
                $scheduler->schedule($task);
            }
        );
    }

    function childTask() {
        $tid = (yield getTaskId());
        while (true) {
            echo "Child task $tid still alive!\n";
            yield;
        }
    }
     
    function task() {
        $tid = (yield getTaskId());
        $childTid = (yield newTask(childTask()));
     
        for ($i = 1; $i <= 6; ++$i) {
            echo "Parent task $tid iteration $i.\n";
            yield;
     
            if ($i == 3) yield killTask($childTid);
        }
    }


    /**
     * 任务调度相关，主协程下面 有多个子协程
     * @DateTime 2018-04-23
     * @return   [type]           [description]
     */
    function main()
    {
        $scheduler = new Scheduler;
        $scheduler->newTask(task());
        $scheduler->run();
    }

    main();

?>