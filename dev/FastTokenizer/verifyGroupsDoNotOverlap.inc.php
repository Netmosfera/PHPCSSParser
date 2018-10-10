<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\FastTokenizer;

function verifyGroupsDoNotOverlap(array $sets){
    foreach($sets as $codePointsOfSet){
        foreach($sets as $codePointsOfOtherSet){
            if($codePointsOfSet === $codePointsOfOtherSet){ continue; }
            assert($codePointsOfSet->containsNone($codePointsOfOtherSet));
        }
    }
}


