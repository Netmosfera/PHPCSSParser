<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer\Data\CodePointSets;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\StandardTokenizer\Data\CompressedCodePointSet;
use Netmosfera\PHPCSSAST\StandardTokenizer\Data\ContiguousCodePointsSet;
use function Netmosfera\PHPCSSAST\StandardTokenizer\Data\cp;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getASCIINameStartersSet(){
    $set = new CompressedCodePointSet();
    $set->add(cp("_"));
    $set->addAll(getLettersSet());
    $set->addAll(new ContiguousCodePointsSet(cp("\u{80}"), cp("\u{FF}")));
    return $set;
}
