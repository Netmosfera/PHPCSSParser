<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\BadURLToken;
use Netmosfera\PHPCSSAST\Tokens\URLToken;
use Netmosfera\PHPCSSAST\Traverser;
use Closure;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * -
 *
 * Assumes that the initial `url(` has already been consumed.
 *
 * @TODO cannot use eatEscape() in $eatEscapeFunction as that allows \EOF and \newline
 */
function eatURLToken(
    Traverser $t,
    String $eatWhitespaceRegexSet,
    String $blacklistCPsRegexSet,
    Closure $eatBadURLRemnantsFunction,
    Closure $eatEscapeFunction
){
    // var_export(preg_quote("\"'\\()"));
    // These CPs must appear as escape sequences if needed
    $excludeCPs = '"\'\\\\\\(\\)';

    $wsBefore = $t->eatExp('[' . $eatWhitespaceRegexSet . ']*');

    $pieces = [];

    LOOP:

    if($t->isEOF()){
        return new URLToken($wsBefore, $pieces, TRUE, "");
    }

    if(has($t->eatStr(")"))){
        return new URLToken($wsBefore, $pieces, FALSE, "");
    }

    $wt = $t->createBranch();
    $wsAfter = $wt->eatExp('[' . $eatWhitespaceRegexSet . ']*');
    if($wsAfter !== ""){
        if($wt->isEOF()){
            $t->importBranch($wt);
            return new URLToken($wsBefore, $pieces, TRUE, $wsAfter);
        }elseif(has($wt->eatStr(")"))){
            $t->importBranch($wt);
            return new URLToken($wsBefore, $pieces, FALSE, $wsAfter);
        }
        $remnants = $eatBadURLRemnantsFunction($t);
        return new BadURLToken($wsBefore, $pieces, $remnants);
    }

    $bt = $t->createBranch();
    if(has($bt->eatStr("\\"))){
        if(has($escape = $eatEscapeFunction($bt))){
            $pieces[] = $escape;
            $t->importBranch($bt);
            goto LOOP;
        }else{
            $remnants = $eatBadURLRemnantsFunction($t);
            return new BadURLToken($wsBefore, $pieces, $remnants);
        }
    }

    $bt = $t->createBranch();
    if(has($bt->eatExp('[' . $excludeCPs . $blacklistCPsRegexSet . ']'))){
        $remnants = $eatBadURLRemnantsFunction($t);
        return new BadURLToken($wsBefore, $pieces, $remnants);
    }

    $piece = $t->eatExp('[^' . $eatWhitespaceRegexSet . $excludeCPs . $blacklistCPsRegexSet . ']+');
    // This must include everything but the CPs already handled
    // in the previous steps, therefore it can never be empty
    assert($piece !== NULL);
    $pieces[] = $piece;

    goto LOOP;
}
