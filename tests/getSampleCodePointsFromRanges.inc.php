<?php

namespace Netmosfera\PHPCSSASTTests;

use IntlChar;
use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use function PHPToolBucket\Testing\getBoundariesByRange;

function getSampleCodePointsFromRanges(CompressedCodePointSet $set){
    $characters = [];
    foreach($set->ranges() as $range){
        array_push($characters, ...getBoundariesByRange(
            $range->start()->code(),
            $range->end()->code(),
            function($o){ return IntlChar::chr($o); }
        ));
    }
    return $characters;
}
