<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\Sets;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use function Netmosfera\PHPCSSASTDev\cp;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getNewlineCodePointSet(){
    $set = new CompressedCodePointSet();
    $set->add(cp("\n"));
    $set->add(cp("\r"));
    $set->add(cp("\f"));
    return $set;
}
