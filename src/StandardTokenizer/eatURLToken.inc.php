<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\AnyURLToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedURLToken;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedWhitespaceToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedBadURLToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedURLBitToken;

function eatURLToken(
    Traverser $traverser,
    IdentifierToken $URL,
    String $whitespaceRegexSet,
    String $blacklistCPsRegexSet,
    Closure $eatEscapeFunction,
    Closure $eatBadURLRemnantsFunction
): ?AnyURLToken{

    // @TODO inject delimiters
    $wsBefore = $traverser->eatExp('[' . $whitespaceRegexSet . ']*+(?!["\'])');
    if($wsBefore === NULL){
        return NULL;
    }
    $wsBefore = $wsBefore === "" ? NULL : new CheckedWhitespaceToken($wsBefore);

    // @TODO assert that $blacklistCPsRegexSet contains \ )
    // and the other delimiters used in this function

    // @TODO also assert that $blacklistCPsRegexSet contains $whitespaceRegexSet

    $pieces = [];

    while(TRUE){

        if($traverser->isEOF()){
            return new CheckedURLToken($URL, $wsBefore, $pieces, NULL, TRUE);
        }

        if($traverser->eatStr(")") !== NULL){
            return new CheckedURLToken($URL, $wsBefore, $pieces, NULL, FALSE);
        }

        $finishTraverser = $traverser->createBranch();
        $wsAfter = $finishTraverser->eatExp('[' . $whitespaceRegexSet . ']*');
        if($wsAfter !== ""){
            $wsAfter = new CheckedWhitespaceToken($wsAfter);
            if($finishTraverser->isEOF()){
                $traverser->importBranch($finishTraverser);
                return new CheckedURLToken($URL, $wsBefore, $pieces, $wsAfter, TRUE);
            }elseif($finishTraverser->eatStr(")") !== NULL){
                $traverser->importBranch($finishTraverser);
                return new CheckedURLToken($URL, $wsBefore, $pieces, $wsAfter, FALSE);
            }
            $remnants = $eatBadURLRemnantsFunction($traverser);
            return new CheckedBadURLToken($URL, $wsBefore, $pieces, $remnants);
        }

        if($traverser->createBranch()->eatStr("\\") !== NULL){
            $escape = $eatEscapeFunction($traverser);
            if($escape !== NULL){
                $pieces[] = $escape;
                continue;
            }else{
                $remnants = $eatBadURLRemnantsFunction($traverser);
                return new CheckedBadURLToken($URL, $wsBefore, $pieces, $remnants);
            }
        }

        if($traverser->createBranch()->eatExp(
            '[' . $blacklistCPsRegexSet . ']'
        ) !== NULL){
            $remnants = $eatBadURLRemnantsFunction($traverser);
            return new CheckedBadURLToken($URL, $wsBefore, $pieces, $remnants);
        }

        $piece = $traverser->eatExp('[^' . $blacklistCPsRegexSet . ']+');
        // This must include everything but the CPs already handled
        // in the previous steps, therefore it can never be empty
        assert($piece !== NULL);
        $pieces[] = new CheckedURLBitToken($piece);
    }
}
