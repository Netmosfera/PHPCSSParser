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
    String $blacklistedCodePointsRegexSet,
    Closure $eatEscapeToken,
    Closure $eatBadURLRemnantsToken
): ?AnyURLToken{

    // @TODO inject delimiters
    $wsBefore = $traverser->eatPattern('[' . $whitespaceRegexSet . ']*+(?!["\'])');
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

        if($traverser->eatString(")") !== NULL){
            return new CheckedURLToken($URL, $wsBefore, $pieces, NULL, FALSE);
        }

        $finishTraverser = $traverser->createBranch();
        $wsAfter = $finishTraverser->eatPattern('[' . $whitespaceRegexSet . ']*');
        if($wsAfter !== ""){
            $wsAfter = new CheckedWhitespaceToken($wsAfter);
            if($finishTraverser->isEOF()){
                $traverser->importBranch($finishTraverser);
                return new CheckedURLToken($URL, $wsBefore, $pieces, $wsAfter, TRUE);
            }elseif($finishTraverser->eatString(")") !== NULL){
                $traverser->importBranch($finishTraverser);
                return new CheckedURLToken($URL, $wsBefore, $pieces, $wsAfter, FALSE);
            }
            $remnants = $eatBadURLRemnantsToken($traverser);
            return new CheckedBadURLToken($URL, $wsBefore, $pieces, $remnants);
        }

        if($traverser->createBranch()->eatString("\\") !== NULL){
            $escape = $eatEscapeToken($traverser);
            if($escape !== NULL){
                $pieces[] = $escape;
                continue;
            }else{
                $remnants = $eatBadURLRemnantsToken($traverser);
                return new CheckedBadURLToken($URL, $wsBefore, $pieces, $remnants);
            }
        }

        if($traverser->createBranch()->eatPattern('[' . $blacklistedCodePointsRegexSet . ']') !== NULL){
            $remnants = $eatBadURLRemnantsToken($traverser);
            return new CheckedBadURLToken($URL, $wsBefore, $pieces, $remnants);
        }

        $piece = $traverser->eatPattern('[^' . $blacklistedCodePointsRegexSet . ']+');
        // This must include everything but the CPs already handled
        // in the previous steps, therefore it can never be empty
        assert($piece !== NULL);
        $pieces[] = new CheckedURLBitToken($piece);
    }
}
