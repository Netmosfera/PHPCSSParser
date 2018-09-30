<?php

namespace Netmosfera\PHPCSSASTTests;

use PHPUnit\Framework\TestCase;
use Throwable;
use Closure;

function assertThrowsType(String $class, Closure $code){
    try{
        $code();
        TestCase::assertInstanceOf($class, NULL);
    }catch(Throwable $throwable){
        if($throwable instanceof $class){
            TestCase::assertInstanceOf($class, $throwable);
        }else{
            throw $throwable;
        }
    }
}
