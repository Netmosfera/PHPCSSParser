<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Error;
use IntlChar;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function cp(String $cp): CodePoint{
    $ord = IntlChar::ord($cp);
    if($ord === NULL){ throw new Error("Invalid code point"); }
    return new CodePoint($ord);
}
