<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\Sets;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use function Netmosfera\PHPCSSASTDev\cp;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getWhitespaceCodePointSet(){
    $set = new CompressedCodePointSet();
    $set->add(cp(" "));
    $set->add(cp("\t"));
    $set->addAll(getNewlineCodePointSet());
    return $set;
}
