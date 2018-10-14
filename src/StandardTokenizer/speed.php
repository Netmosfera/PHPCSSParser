<?php declare(strict_types = 1);


echo PCRE_VERSION;

$str = "";
$byteIndex = 0;
for($i = 0; $i <= 129381; $i++){
    $cp = random_int(1, IntlChar::CODEPOINT_MAX);
    $char = IntlChar::chr($cp);
    if(IntlChar::ord($char) === $cp){
        if($i < 19281){
            $byteIndex += strlen($char);
        }
        $str .= $char;
    }
}
$length = 10;

assert(preg_match("/.*/usD", $str) === 1);

$st = microtime(TRUE);
for($i = 0; $i < 10000; $i++){
    $trim = substr($str, $byteIndex, $length * 4);
    $result = mb_substr($trim, 0, $length);
}
echo number_format(microtime(TRUE) - $st, 10) . "\n";


$st = microtime(TRUE);
for($i = 0; $i < 10000; $i++){
    preg_match("/(.{" . $length . "})/usD", $str, $matches, 0, $byteIndex);
    $result = $matches[1];
}
echo number_format(microtime(TRUE) - $st, 10) . "\n";
