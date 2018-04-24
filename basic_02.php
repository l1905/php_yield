<?php 
    
    include "task_02.php";
    include "scheduler_02.php";

    function task1() {
        for ($i = 1; $i <= 10; ++$i) {
            echo "This is task 1 iteration $i.\n";
            yield $i;
        }
    }
     
    function task2() {
        for ($i = 100; $i <= 110; ++$i) {
            echo "This is task 2 iteration $i.\n";
            yield $i;
        }
    }

    /**
     * 简单的任务调度
     * copy自鸟哥代码 http://www.laruence.com/2015/05/28/3038.html
     * @DateTime 2018-04-23
     * @return   [type]           [description]
     */
    function main() {
        #生成调度器
        $scheduler = new Scheduler;
         
        #新增任务
        $scheduler->newTask(task1());
        $scheduler->newTask(task2());
        
        #交替执行两个迭代器
        $scheduler->run();
    }

    main();

?>