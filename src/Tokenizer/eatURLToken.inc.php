<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\eatEscape;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\eatNonPrintableCodePoint;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\isValidEscape;
use Netmosfera\PHPCSSAST\Tokens\BadURLToken;
use Netmosfera\PHPCSSAST\Tokens\URLToken;
use Netmosfera\PHPCSSAST\Traverser;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * -
 *
 * Assumes that the initial `url(` has already been consumed.
 */
function eatURLToken(Traverser $t, Closure $eatBadURLRemnants){

    $wsBefore = eatWhitespaceToken($t);

    $pieces = [];

    LOOP:

    if($t->isEOF()){
        return new URLToken($wsBefore, $pieces, TRUE, NULL);
    }

    if(has($t->eatStr(")"))){
        return new URLToken($wsBefore, $pieces, FALSE, NULL);
    }

    $wt = $t->createBranch();
    $wsAfter = eatWhitespaceToken($wt);
    if(has($wsAfter)){

        if($wt->isEOF()){
            $t->importBranch($wt);
            return new URLToken($wsBefore, $pieces, TRUE, $wsAfter);
        }

        if(has($wt->eatStr(")"))){
            $t->importBranch($wt);
            return new URLToken($wsBefore, $pieces, FALSE, $wsAfter);
        }

        $remnants = $eatBadURLRemnants($t);
        return new BadURLToken($wsBefore, $pieces, $remnants);
    }

    $bt = $t->createBranch();
    if(
        has($bt->eatExp('[\'"(]')) ||
        has(eatNonPrintableCodePoint($bt))
    ){
        // Branch was created because we want the remnants to contain
        // the code points that were consumed for the test.
        $remnants = $eatBadURLRemnants($t);
        return new BadURLToken($wsBefore, $pieces, $remnants);
    }

    $bt = $t->createBranch();
    if(has($bt->eatStr("\\"))){
        if(isValidEscape($bt)){
            $pieces[] = eatEscape($bt);
            $t->importBranch($bt);
        }else{
            $remnants = $eatBadURLRemnants($t);
            return new BadURLToken($wsBefore, $pieces, $remnants);
        }
    }

    $miscCharacters = $t->escapeRegexp("\"'()\\");
    $nonPrintableExp = '\x{0}-\x{8}\x{E}-\x{1F}\x{B}\x{7F}';
    $piece = $t->eatExp('[^' . $miscCharacters . $nonPrintableExp . ']+');
    assert($piece !== NULL);
    $pieces[] = $piece;

    goto LOOP;
}
