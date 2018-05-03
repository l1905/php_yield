<?php
    //https://www.jianshu.com/p/86fefb0aacd9
    /*Generator implements Iterator {
        
        public mixed current ( void )//返回当前产生的值
       
        public mixed key ( void ) //返回当前产生的键
        
        public void next ( void )//生成器继续执行
       
        public void rewind ( void ) //重置迭代器,如果迭代已经开始了，这里会抛出一个异常。
       
        // 1. 向生成器中传入一个值，当前yield接收值，赋值receive=MockGetValue() 。 这个MockGetValue()是假想函数，用来接收send()发送进来的值
        // 2. 继续执行，遇到下一个yield时，向函数外抛出（返回）value
        // 3. 暂停(pause)，等待next()或send()恢复
        public mixed send ( mixed $value )
      
        public void throw ( Exception $exception )  //向生成器中抛入一个异常
      
        public bool valid ( void )  //检查迭代器是否被关闭,已被关闭返回 FALSE，否则返回 TRUE
       
        public void __wakeup ( void ) //序列化回调
        
        public mixed getReturn ( void )//返回generator函数的返回值，PHP version 7+
    }*/


    function test_01() {
        //...
        //上下文相关代码...
        //...
        $a = (yield 111);  //==================01 第一个yield
        var_dump('test()->$a:'.$a); //=========02
        $b = (yield 222);  //==================03
        var_dump('test()->$b:'.$b);// =========04

        return "test_01";
    }

    function basic_test() {
        $gen = test_01(); //生成迭代器 即我们最上面的定义的class，而不是返回函数
        #验证一下
        var_dump($gen instanceof Iterator);

        // 每次的执行都非常像 js,c调试代码时，打断点操作，每次点击运行，会运行到下个断点位置, 每次均保留上下文环境
        //==========================================
        echo "first:";
        var_dump($gen->current()); //获取当前值 预计输出 11111， 在01等号右侧处暂停
        echo PHP_EOL; 

        //==========================================
        echo "valid_02:";
        var_dump($gen->valid());  //是否还有剩余yield未执行到 , 我们预计输出true
        echo PHP_EOL;

        //==========================================
        echo "second:";
        var_dump($gen->send(333)); //向迭代器发送数据，并且有返回值， 预计输出2222， 在03等号右侧暂停
        echo PHP_EOL;
        // exit();

        //==========================================
        echo "third:";
        var_dump($gen->next()); // 详单于send(NULL) 但是没有返回值， 预计输出NULL
        echo PHP_EOL;
        // exit();

        //==========================================
        echo "valid_02:";
        var_dump($gen->valid()); //迭代器已经执行完毕， 没有可以循环yield, 因此输出false
        echo PHP_EOL;

        //==========================================
        echo "return_value:";
        var_dump($gen->getReturn());
    }

    function test_02() {
        //PHP7中，通过生成器委托（yield from），可以将其他生成器、可迭代的对象、数组委托给外层生成器。外层的生成器会先顺序 yield 委托出来的值，然后继续 yield 本身中定义的值。 就是可以迭代嵌套的生成器
        $result = yield from test_01(); //=========05

        yield "test_02"; //=========06

        return $result;  //=========07
    }

    function advance_test() {
        $gen = test_02(); //生成迭代器 即我们最上面的定义的class，而不是返回函数
        #验证一下
        var_dump($gen instanceof Iterator);

        //==========================================
        echo "first:";
        var_dump($gen->current()); //获取当前值 预计输出 11111， 在01等号右侧处暂停
        echo PHP_EOL;

        //==========================================
        echo "valid_02:";
        var_dump($gen->valid());  //是否还有剩余yield未执行到 ，预计输出true
        echo PHP_EOL;

        //==========================================
        echo "second:";
        var_dump($gen->send(333)); //向迭代器发送数据，并且有返回值， 预计输出222， 在03等号右侧暂停
        echo PHP_EOL;

        //==========================================
        echo "third:";
        var_dump($gen->send('')); // 详单于send(NULL) 有返回值， 预计输出test_02, 在06右侧暂停
        echo PHP_EOL;

        //==========================================
        echo "continue :";
        var_dump($gen->send('')); //继续执行

        //==========================================
        echo "return_result:";
        var_dump($gen->getReturn()); //获取返回值 预计输出 test_01
    }

    function basic_basic_test() {
        //遍历数据 类似python中的 xrange
        /*function xrange($start, $end, $step = 1) {
            for ($i = $start; $i <= $end; $i += $step) {
                yield $i;
            }
        }
         
        foreach (xrange(1, 1000000) as $num) {
            echo $num, "\n";
            sleep(1);
        }*/

        //读取大文件
        /*function getLines($file) {
            $f = fopen($file, 'r');
            try {
                while ($line = fgets($f)) {
                    yield $line;
                }
            } finally {
                fclose($f);
            }
        }

        foreach (getLines("file.txt") as $n => $line) {
            if ($n > 15) break;
            echo $line;
        }*/

    }
    

    function main() {
        #我们对yield的第一感官
        // basic_basic_test();


        // foreach 驱动，
        // 自身函数驱动 
        
        #基础测试
        // basic_test();
        // 看我们第一个应用例子
        // yield
        // send
        

        #进阶测试
        advance_test();
        //看我们第二个应用的例子
        //yield
        // current
        // send
        // valid
        // yield from
        // getReturn
    }

    main();







