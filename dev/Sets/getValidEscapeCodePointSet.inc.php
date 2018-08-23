<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\Sets;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use function Netmosfera\PHPCSSASTDev\cp;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getValidEscapeCodePointSet(): CompressedCodePointSet{
    $set = new CompressedCodePointSet();
    $set->selectAll();
    $set->remove(cp("\n"));
    $set->remove(cp("\f"));
    $set->remove(cp("\r"));
    return $set;
}
