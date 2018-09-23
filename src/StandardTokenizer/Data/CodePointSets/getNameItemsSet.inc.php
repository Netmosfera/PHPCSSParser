<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer\Data\CodePointSets;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\StandardTokenizer\Data\CompressedCodePointSet;
use function Netmosfera\PHPCSSAST\StandardTokenizer\Data\cp;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getNameItemsSet(){
    $set = new CompressedCodePointSet();
    $set->add(cp("-"));
    $set->addAll(getNameStartersSet());
    $set->addAll(getDigitsSet());
    return $set;
}
