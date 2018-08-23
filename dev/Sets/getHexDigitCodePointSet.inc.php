<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\Sets;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use Netmosfera\PHPCSSASTDev\ContiguousCodePointsSet;
use function Netmosfera\PHPCSSASTDev\cp;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getHexDigitCodePointSet(){
    $set = new CompressedCodePointSet();
    $set->addAll(new ContiguousCodePointsSet(cp("0"), cp("9")));
    $set->addAll(new ContiguousCodePointsSet(cp("a"), cp("f")));
    $set->addAll(new ContiguousCodePointsSet(cp("A"), cp("F")));
    return $set;
}
