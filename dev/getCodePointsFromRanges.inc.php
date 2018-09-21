<?php

namespace Netmosfera\PHPCSSASTDev;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use IntlChar;

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
