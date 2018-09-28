<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\AnyURLToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\URLBitToken;
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

    $wsBefore = $traverser->eatExp('[' . $whitespaceRegexSet . ']*+(?!["\'])'); // @TODO inject delimiters
    if($wsBefore === NULL){
        return NULL;
    }
    $wsBefore = $wsBefore === "" ? NULL : new WhitespaceToken($wsBefore);

    // @TODO assert that $blacklistCPsRegexSet contains \ ) and the other delimiters used in this function

    // @TODO also assert that $blacklistCPsRegexSet contains $whitespaceRegexSet

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

        if($traverser->createBranch()->eatExp('[' . $blacklistCPsRegexSet . ']') !== NULL){
            $remnants = $eatBadURLRemnantsFunction($traverser);
            return new BadURLToken($wsBefore, $pieces, $remnants);
        }

        $piece = $traverser->eatExp('[^' . $blacklistCPsRegexSet . ']+');
        // This must include everything but the CPs already handled
        // in the previous steps, therefore it can never be empty
        assert($piece !== NULL);
        $pieces[] = new URLBitToken($piece);
    }
}
