<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer\Data\CodePointSets;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\StandardTokenizer\Data\CompressedCodePointSet;
use Netmosfera\PHPCSSAST\StandardTokenizer\Data\ContiguousCodePointsSet;
use function Netmosfera\PHPCSSAST\StandardTokenizer\Data\cp;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getHexDigitsSet(){
    $set = new CompressedCodePointSet();
    $set->addAll(new ContiguousCodePointsSet(cp("0"), cp("9")));
    $set->addAll(new ContiguousCodePointsSet(cp("a"), cp("f")));
    $set->addAll(new ContiguousCodePointsSet(cp("A"), cp("F")));
    return $set;
}
