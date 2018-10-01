<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

use Closure;

// phpcs:disable Generic.Metrics.NestingLevel
function makePiecesSample(Closure $getPiecesFunction, Bool $doGiveEmpty = TRUE){
    if($doGiveEmpty){
        yield [];
    }

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
// phpcs:enable
