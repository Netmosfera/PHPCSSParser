<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\Sets;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getLetterCodePointSet(): CompressedCodePointSet{
    $set = new CompressedCodePointSet();
    $set->addAll(getLCLetterCodePointSet());
    $set->addAll(getUCLetterCodePointSet());
    return $set;
}
