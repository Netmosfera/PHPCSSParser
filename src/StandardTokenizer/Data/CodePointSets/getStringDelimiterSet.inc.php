<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer\Data\CodePointSets;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\StandardTokenizer\Data\CompressedCodePointSet;
use function Netmosfera\PHPCSSAST\StandardTokenizer\Data\cp;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getStringDelimiterSet(){
    $set = new CompressedCodePointSet();
    $set->add(cp("\""));
    $set->add(cp("'"));
    return $set;
}
