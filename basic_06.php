<?php 
    
    function echoTimes($msg, $max) {
        for ($i = 1; $i <= $max; ++$i) {
            echo "$msg iteration $i\n";
            yield;
        }
    }
     
    function task() {
        yield from echoTimes('foo', 10); // print foo ten times
        echo "---\n";
        yield from echoTimes('bar', 5); // print bar five times
    }

    //简单使用
    function test() {
        foreach (task() as $item) {
            ;
        }
        /*$task_generator = task();

        $task_generator->send('aaa');

        $task_generator->send('bbb');

        $task_generator->send('ccc');*/
    }

    function echoMsg($msg) {
        while (true) {
            $i = yield;
            if($i === null){
                break;
            }
            if(!is_numeric($i)){
                throw new Exception("Hoo! must give me a number");
            }
            echo "$msg iteration $i\n";
        }
    }
    function task2() {
        yield from echoMsg('foo');
        echo "---\n";
        yield from echoMsg('bar');
    }

    //同样可以和嵌套迭代器通信
    function test2() {
        $gen = task2();
        foreach (range(1,10) as $num) {
            $gen->send($num);
        }
        $gen->send(null);
        foreach (range(1,5) as $num) {
            $gen->send($num);
        }
    }

    function echoTimes3($msg, $max) {
        for ($i = 1; $i <= $max; ++$i) {
            echo "$msg iteration $i\n";
            yield;
        }
        return "$msg the end value : $i\n";
    }

    //可以有两种方法获取这个返回值：
    //1. 使用 $ret = Generator::getReturn() 方法。
    //2. 使用 $ret = yield from Generator() 表达式。
    function task3() {
        $end = yield from echoTimes3('foo', 10);
        echo $end;
        $gen = echoTimes3('bar', 5);
        yield from $gen;
        echo $gen->getReturn();
    }

    function test3() {
        foreach (task3() as $item) {
            ;
        }
    }


    function main() {
        // test();
        // test2();
        test3();
    }

    
    /**
     * yield from语法学习
     * PHP7中，通过生成器委托（yield from），可以将其他生成器、可迭代的对象、数组委托给外层生成器。外层的生成器会先顺序 yield 委托出来的值，然后继续 yield 本身中定义的值。 就是可以迭代嵌套的生成器
     * 代码来自： https://segmentfault.com/a/1190000010479841#articleHeader3
     */
    main();
?>