<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\Sets;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use Netmosfera\PHPCSSASTDev\ContiguousCodePointsSet;
use function Netmosfera\PHPCSSASTDev\cp;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getUCLetterCodePointSet(): CompressedCodePointSet{
    $set = new CompressedCodePointSet();
    $set->addAll(new ContiguousCodePointsSet(cp("A"), cp("Z")));
    return $set;
}
