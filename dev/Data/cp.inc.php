<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\Data;

use Error;
use IntlChar;

function cp(String $cp): CodePoint{
    $ord = IntlChar::ord($cp);
    if($ord === NULL){ throw new Error("Invalid code point"); }
    return new CodePoint($ord);
}
