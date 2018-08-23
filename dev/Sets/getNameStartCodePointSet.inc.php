<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\Sets;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use function Netmosfera\PHPCSSASTDev\cp;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getNameStartCodePointSet(){
    $set = new CompressedCodePointSet();
    $set->add(cp("_"));
    $set->addAll(getLetterCodePointSet());
    $set->addAll(getNonASCIICodePointSet());
    return $set;
}
