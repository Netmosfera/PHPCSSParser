<?php

namespace Netmosfera\PHPCSSASTDev;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getBoundariesRange(
    Int $startOffset,
    Int $endOffset,
    Closure $generate,
    Int $divisions = 2,
    Int $howMany = 2
){
    $count = $endOffset - $startOffset + 1;
    return getBoundariesCount($startOffset, $count, $generate, $divisions, $howMany);
}
