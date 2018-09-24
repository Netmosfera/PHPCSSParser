<?php

namespace Netmosfera\PHPCSSASTTests;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use IntlChar;
use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getCodePointsFromRanges(CompressedCodePointSet $set){
    $characters = [];
    foreach($set->getRanges() as $range){
        array_push($characters, ...getBoundariesRange(
            $range->getStart()->getCode(),
            $range->getEnd()->getCode(),
            function($o){ return IntlChar::chr($o); }
        ));
    }
    return $characters;
}
