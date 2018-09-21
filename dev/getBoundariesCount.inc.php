<?php

namespace Netmosfera\PHPCSSASTDev;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

// boundary value analysis, given a large range of possible test case values
// this function will reduce it to a smaller set of values that spans the whole range
function getBoundariesCount(
    Int $startOffset,
    Int $count,
    Closure $generate,
    Int $divisions = 2,
    Int $howMany = 2
){
    $divisibleRangeLength = $count - $howMany;
    $minSectionLength = (Int)floor($divisibleRangeLength / $divisions);

    if($howMany >= $minSectionLength){
        $range = [];
        for($o = 0; $o < $count; $o++){
            $range[] = $generate($startOffset + $o);
        }
        return $range;
    }

    $currentRangeLength = $minSectionLength * $divisions;
    $sectionLengths = array_fill(0, $divisions, $minSectionLength);

    while($currentRangeLength < $divisibleRangeLength){
        usort($sectionLengths, function($a, $b){ return $a <=> $b; });
        $sectionLengths[0] += 1;
        $currentRangeLength += 1;
    }

    $startOffsets = [0];
    $carry = 0;
    foreach($sectionLengths as $sectionLength){
        $carry = $carry + $sectionLength;
        $startOffsets[] = $carry;
    }

    $collect = [];
    foreach($startOffsets as $xxx){
        for($c = 0; $c < $howMany; $c++){
            $collect[] = $generate($startOffset + $xxx + $c);
        }
    }

    return $collect;
}
