<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function makePiecesSample(Closure $getPiecesFunction){
    yield [];
    foreach($getPiecesFunction(NULL) as $p0){
        yield [$p0];
        foreach($getPiecesFunction($p0) as $p1){
            yield [$p0, $p1];
            foreach($getPiecesFunction($p1) as $p2){
                yield [$p0, $p1, $p2];
                foreach($getPiecesFunction($p2) as $p3){
                    yield [$p0, $p1, $p2, $p3];
                    foreach($getPiecesFunction($p3) as $p4){
                        yield [$p0, $p1, $p2, $p3, $p4];
                    }
                }
            }
        }
    }
}
