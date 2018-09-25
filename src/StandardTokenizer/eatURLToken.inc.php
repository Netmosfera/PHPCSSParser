<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\Names\AnyURLToken;
use Netmosfera\PHPCSSAST\Tokens\Names\BadURLToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLToken;
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

    // var_export(preg_quote("\\)"));
    // These CPs must appear as escape sequences if actually needed in a URL
    $excludeCPs = '\\\\\\)';

    $pieces = [];

    for(;;){

        if($traverser->isEOF()){
            return new URLToken($wsBefore, $pieces, "", TRUE);
        }

        if($traverser->eatStr(")") !== NULL){
            return new URLToken($wsBefore, $pieces, "", FALSE);
        }

        $finishTraverser = $traverser->createBranch();
        $wsAfter = $finishTraverser->eatExp('[' . $whitespaceRegexSet . ']*');
        if($wsAfter !== ""){
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
