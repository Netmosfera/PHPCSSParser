<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\AnyURLToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\URLToken;
use Closure;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Consumes an {@see URLToken}, if any.
 *
 * Assumes that `url(` has been consumed already.
 */
function eatURLToken(
    Traverser $traverser,
    String $whitespaceRegexSet,
    String $blacklistCPsRegexSet,
    Closure $eatEscapeFunction,
    Closure $eatBadURLRemnantsFunction
): ?AnyURLToken{

    $wsBefore = $traverser->eatExp('[' . $whitespaceRegexSet . ']*+(?!["\'])');
    if($wsBefore === NULL){
        return NULL;
    }
    $wsBefore = $wsBefore === "" ? NULL : new WhitespaceToken($wsBefore);

    // var_export(preg_quote("\\)"));
    // These CPs must appear as escape sequences if actually needed in a URL
    $excludeCPs = '\\\\\\)';

    $pieces = [];

    for(;;){

        if($traverser->isEOF()){
            return new URLToken($wsBefore, $pieces, NULL, TRUE);
        }

        if($traverser->eatStr(")") !== NULL){
            return new URLToken($wsBefore, $pieces, NULL, FALSE);
        }

        $finishTraverser = $traverser->createBranch();
        $wsAfter = $finishTraverser->eatExp('[' . $whitespaceRegexSet . ']*');
        if($wsAfter !== ""){
            $wsAfter = new WhitespaceToken($wsAfter);
            if($finishTraverser->isEOF()){
                $traverser->importBranch($finishTraverser);
                return new URLToken($wsBefore, $pieces, $wsAfter, TRUE);
            }elseif($finishTraverser->eatStr(")") !== NULL){
                $traverser->importBranch($finishTraverser);
                return new URLToken($wsBefore, $pieces, $wsAfter, FALSE);
            }
            $remnants = $eatBadURLRemnantsFunction($traverser);
            return new BadURLToken($wsBefore, $pieces, $remnants);
        }

        if($traverser->createBranch()->eatStr("\\") !== NULL){
            $escape = $eatEscapeFunction($traverser);
            if($escape !== NULL){
                $pieces[] = $escape;
                continue;
            }else{
                $remnants = $eatBadURLRemnantsFunction($traverser);
                return new BadURLToken($wsBefore, $pieces, $remnants);
            }
        }

        if($traverser->createBranch()->eatExp('[' . $excludeCPs . $blacklistCPsRegexSet . ']') !== NULL){
            $remnants = $eatBadURLRemnantsFunction($traverser);
            return new BadURLToken($wsBefore, $pieces, $remnants);
        }

        $piece = $traverser->eatExp('[^' . $whitespaceRegexSet . $excludeCPs . $blacklistCPsRegexSet . ']+');
        // This must include everything but the CPs already handled
        // in the previous steps, therefore it can never be empty
        assert($piece !== NULL);
        $pieces[] = $piece;
    }
}
