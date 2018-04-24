<?php
    class Scheduler {
        protected $maxTaskId = 0;
        protected $taskMap = []; // taskId => task
        protected $taskQueue;
     
        public function __construct() {
            $this->taskQueue = new SplQueue();
        }
     
        public function newTask(Generator $coroutine) {
            $tid = ++$this->maxTaskId;
            $task = new Task($tid, $coroutine);
            $this->taskMap[$tid] = $task;
            $this->schedule($task);
            return $tid;
        }
     
        public function schedule(Task $task) {
            $this->taskQueue->enqueue($task);
        }
     
        public function run() {
            while (!$this->taskQueue->isEmpty()) {
                $task = $this->taskQueue->dequeue();
                $retval = $task->run();
                
                //判断是否是系统调用
                if ($retval instanceof SystemCall) {
                    $retval($task, $this);
                    continue;
                }
         
                if ($task->isFinished()) {
                    unset($this->taskMap[$task->getTaskId()]);
                } else {
                    $this->schedule($task);
                }
            }
        }
        
    }
?>