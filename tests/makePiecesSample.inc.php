<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests;

use Closure;
use function PHPToolBucket\Testing\smartCartesianProduct;
use function array_unshift;

function makePiecesSample(Closure $getPiecesFunction, Bool $doGiveEmpty = TRUE, Int $maxLength = 4){
    $lengths = [1, 2, $maxLength];
    if($doGiveEmpty){
        array_unshift($lengths, 0);
    }

    $result = [];
    foreach($lengths as $sequenceLength){
        $sequences = smartCartesianProduct(function(
            array $combination,
            Int $index,
            Int $maxIndex
        ) use($getPiecesFunction){
            $previousElement = $combination === [] ? NULL : end($combination);
            return $getPiecesFunction($previousElement, $index === $maxIndex);
        }, $sequenceLength);

        foreach($sequences as $sequence){
            $result[] = $sequence;
        }
    }
    return $result;
}
