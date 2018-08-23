<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\Sets;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use function Netmosfera\PHPCSSASTDev\cp;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getNameCodePointSet(){
    $set = new CompressedCodePointSet();
    $set->add(cp("-"));
    $set->addAll(getNameStartCodePointSet());
    $set->addAll(getDigitCodePointSet());
    return $set;
}
