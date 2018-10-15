<?php declare(strict_types = 1);

$foo = [];

$st = microtime(TRUE);
for($i = 0; $i < 10000000; $i++){
    if(isset($foo["bar"]));else{
        $result = "foo";
    }
}
echo number_format($r1 = microtime(TRUE) - $st, 10) . "\n";

$st = microtime(TRUE);
for($i = 0; $i < 10000000; $i++){
    if(!isset($foo["bar"])){
        $result = "foo";
    }
}
echo number_format($r2 = microtime(TRUE) - $st, 10) . "\n";



