<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests;

use Closure;

function makePiecesSample(
    Closure $getPiecesFunction,
    Bool $doGiveEmpty = TRUE
){
    if($doGiveEmpty){
        $result[] = [];
    }

    $isLast = TRUE;
    $isNotLast = FALSE;

    foreach($getPiecesFunction(NULL, $isLast) as $p0){
        $result[] = [$p0];
    }

    foreach($getPiecesFunction(NULL, $isNotLast) as $p0){
        foreach($getPiecesFunction($p0, $isLast) as $p1){
            $result[] = [$p0, $p1];
        }
    }

    foreach($getPiecesFunction(NULL, $isNotLast) as $p0){
        foreach($getPiecesFunction($p0, $isNotLast) as $p1){
            foreach($getPiecesFunction($p1, $isLast) as $p2){
                $result[] = [$p0, $p1, $p2];
            }
        }
    }

    foreach($getPiecesFunction(NULL, $isNotLast) as $p0){
        foreach($getPiecesFunction($p0, $isNotLast) as $p1){
            foreach($getPiecesFunction($p1, $isNotLast) as $p2){
                foreach($getPiecesFunction($p2, $isLast) as $p3){
                    $result[] = [$p0, $p1, $p2, $p3];
                }
            }
        }
    }

    return $result;
}
