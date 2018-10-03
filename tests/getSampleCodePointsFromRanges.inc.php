<?php

namespace Netmosfera\PHPCSSASTTests;

use IntlChar;
use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use function PHPToolBucket\Testing\getBoundariesByRange;

function getSampleCodePointsFromRanges(CompressedCodePointSet $set){
    $characters = [];
    foreach($set->getRanges() as $range){
        array_push($characters, ...getBoundariesByRange(
            $range->getStart()->getCode(),
            $range->getEnd()->getCode(),
            function($o){ return IntlChar::chr($o); }
        ));
    }
    return $characters;
}
