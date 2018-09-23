<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer\Data\CodePointSets;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\StandardTokenizer\Data\CompressedCodePointSet;
use Netmosfera\PHPCSSAST\StandardTokenizer\Data\ContiguousCodePointsSet;
use function Netmosfera\PHPCSSAST\StandardTokenizer\Data\cp;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getNonASCIIsSet(): CompressedCodePointSet{
    $set = new CompressedCodePointSet();
    $set->addAll(new ContiguousCodePointsSet(cp("\u{80}"), cp("\u{10FFFF}")));
    return $set;
}
