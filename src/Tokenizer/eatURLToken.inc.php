<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\AnyURLToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\URLBitToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\URLToken;

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

    // @TODO this is wrong:
    if(isset($wsBefore));else{
        return NULL;
    }
    if($wsBefore === ""){
        $wsBefore = NULL;
    }else{
        $wsBefore = new WhitespaceToken($wsBefore);
    }

    // @TODO assert that $blacklistCPsRegexSet contains \ )
    // and the other delimiters used in this function

    // @TODO also assert that $blacklistCPsRegexSet contains $whitespaceRegexSet

    $pieces = [];

    while(TRUE){
        if(isset($traverser->data[$traverser->index]));else{
            return new URLToken($URL, $wsBefore, $pieces, NULL, TRUE);
        }

        if($traverser->eatString(")") !== NULL){
            return new URLToken($URL, $wsBefore, $pieces, NULL, FALSE);
        }

        $finishTraverser = $traverser->createBranch();
        $wsAfter = $finishTraverser->eatPattern('[' . $whitespaceRegexSet . ']*');
        if($wsAfter !== ""){
            $wsAfter = new WhitespaceToken($wsAfter);
            if(isset($finishTraverser->data[$finishTraverser->index]));else{
                $traverser->importBranch($finishTraverser);
                return new URLToken($URL, $wsBefore, $pieces, $wsAfter, TRUE);
            }
            if($finishTraverser->eatString(")") !== NULL){
                $traverser->importBranch($finishTraverser);
                return new URLToken($URL, $wsBefore, $pieces, $wsAfter, FALSE);
            }
            $remnants = $eatBadURLRemnantsToken($traverser);
            return new BadURLToken($URL, $wsBefore, $pieces, $remnants);
        }

        if($traverser->createBranch()->eatString("\\") !== NULL){
            $escape = $eatEscapeToken($traverser);
            if(isset($escape)){
                $pieces[] = $escape;
                continue;
            }else{
                $remnants = $eatBadURLRemnantsToken($traverser);
                return new BadURLToken($URL, $wsBefore, $pieces, $remnants);
            }
        }

        if($traverser->createBranch()->eatPattern('[' . $blacklistedCodePointsRegexSet . ']') !== NULL){
            $remnants = $eatBadURLRemnantsToken($traverser);
            return new BadURLToken($URL, $wsBefore, $pieces, $remnants);
        }

        $piece = $traverser->eatPattern('[^' . $blacklistedCodePointsRegexSet . ']+');
        // This must include everything but the CPs already handled
        // in the previous steps, therefore it can never be empty
        assert(isset($piece));
        $pieces[] = new URLBitToken($piece);
    }
}
