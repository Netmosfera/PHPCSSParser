<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\Data\CodePointSets;

use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use function Netmosfera\PHPCSSASTDev\Data\cp;

function getStringBitSet(): CompressedCodePointSet{
    $set = new CompressedCodePointSet();

    $set->selectAll();

    // \ is disallowed in the string-bit as that is an escape seq
    $set->remove(cp("\\"));

    // newline in a string terminates it (in a badstringtoken)
    $set->removeAll(getNewlinesSet());

    return $set;
}
