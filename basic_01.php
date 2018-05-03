<?php
    // https://www.jianshu.com/p/86fefb0aacd9
    /* 
    send 执行流程：
    1. 赋值receive=MockGetValue() 。 这个MockGetValue()是假想函数，用来接收send()发送进来的值
    2. 继续执行，遇到下一个yield时，向函数外抛出（返回）value
    3. 暂停(pause)，等待next()或send()恢复
    迭代器没有return 返回值
    
    
    In PHP 5, a generator could not return a value: doing so would result in a compile error. An empty return statement was valid syntax within a generator and it would terminate the generator. Since PHP 7.0, a generator can return values, which can be retrieved using Generator::getReturn()
    根据官方文档， 到PHP7可以有返回值，利用getReturn()获取返回值

    
    current 执行流程：
    1. 从当前位置向下执行， 遇到下一个yield时， 向函数外抛出(返回)value
    2. 暂停(pause),等待next()或者send(恢复)

    next 执行流程：
    = send(NULL), 但是没有返回值
    
    valid 执行流程：
    检查迭代器是否关闭

    rewind 执行流程：
    将迭代器执行到首次yield之前

    yield from语法学习
    PHP7中，通过生成器委托（yield from），可以将其他生成器、可迭代的对象、数组委托给外层生成器。外层的生成器会先顺序 yield 委托出来的值，然后继续 yield 本身中定义的值。 就是可以迭代嵌套的生成器
     
    */
    function get_test_detail($param = 200) {

        $result_01 = yield 1111;
        var_dump("result_01:".$result_01);
        $result_02 = yield 2222;
        var_dump("result_02:".$result_02);
        $result_03 = yield 3333;
        var_dump("result_03:".$result_03);
        $result_04 = yield 4444;
        var_dump("result_04:".$result_04);
    }

    function test_send() {
        #生成迭代器
        $test_detail_generator = get_test_detail();

        #继续执行， 到yield 2222暂停
        var_dump($test_detail_generator->send('aaaaaaa'));
        
        #继续执行， 到yield 3333暂停
        var_dump($test_detail_generator->send('bbbbbbb'));

        #继续执行， 到yield 4444暂停
        var_dump($test_detail_generator->send('ccccccc'));
        var_dump($test_detail_generator->valid());
        
        #继续执行， valid为false
        var_dump($test_detail_generator->send('ddddddd'));
        var_dump($test_detail_generator->valid());

    }

    function test_current() {
        #生成迭代器
        $test_detail_generator = get_test_detail();

        #进行第一次current输出
        var_dump($test_detail_generator->current());

        #测试进行第二次current输出
        var_dump($test_detail_generator->current());

    }

    function test_yield_from_01() {
        $result = yield from get_test_detail();

        return 'hello 01';
    }

    function test_yield_from() {
        //test_yield_from_01();

        $test_detail_generator = test_yield_from_01();

        #继续执行， 到yield 2222暂停
        var_dump($test_detail_generator->send('aaaaaaa'));
        
        #继续执行， 到yield 3333暂停
        var_dump($test_detail_generator->send('bbbbbbb'));

        #继续执行， 到yield 4444暂停
        var_dump($test_detail_generator->send('ccccccc'));
        var_dump($test_detail_generator->valid());
        
        #继续执行， valid为false
        var_dump($test_detail_generator->send('ddddddd'));
        var_dump($test_detail_generator->valid());

        var_dump($test_detail_generator->getReturn());
    }

    /**
     * 概念相关
     * @DateTime 2018-04-23
     * @return   [type]           [description]
     */
    function main() {
        test_send();

        // test_current();

        // test_yield_from();
    }

    //执行相关迭代器
    main();


?>