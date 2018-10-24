<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Closure;
use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\AnyURLToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedURLToken;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedWhitespaceToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedBadURLToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedURLBitToken;

function eatURLToken(
    Traverser $traverser,
    IdentifierToken $URL,
    ?String $whitespaceRegexSet = NULL,
    ?String $blacklistedCodePointsRegexSet = NULL,
    ?Closure $eatValidEscape = NULL,
    ?Closure $eatBadURLRemnants = NULL,
    String $WhitespaceTokenClass = CheckedWhitespaceToken::CLASS,
    String $URLBitTokenClass = CheckedURLBitToken::CLASS,
    String $URLTokenClass = CheckedURLToken::CLASS,
    String $BadURLTokenClass = CheckedBadURLToken::CLASS
): ?AnyURLToken{

    if(isset($whitespaceRegexSet));else{
        $whitespaceRegexSet = SpecData::WHITESPACES_REGEX_SET;
    }

    if(isset($blacklistedCodePointsRegexSet));else{
        $blacklistedCodePointsRegexSet = SpecData::URL_TOKEN_BIT_NOT_CPS_REGEX_SET;
    }

    if(isset($eatValidEscape));else{
        $eatValidEscape = __NAMESPACE__ . "\\eatValidEscapeToken";
    }

    if(isset($eatBadURLRemnants));else{
        $eatBadURLRemnants = __NAMESPACE__ . "\\eatBadURLRemnantsToken";
    }

    // @TODO inject delimiters
    $wsBefore = $traverser->eatPattern('[' . $whitespaceRegexSet . ']*+(?!["\'])');
    if(isset($wsBefore));else{
        return NULL;
    }
    if($wsBefore === ""){
        $wsBefore = NULL;
    }else{
        $wsBefore = new $WhitespaceTokenClass($wsBefore);
    }

    // @TODO assert that $blacklistCPsRegexSet contains \ )
    // and the other delimiters used in this function

    // @TODO also assert that $blacklistCPsRegexSet contains $whitespaceRegexSet

    $pieces = [];

    while(TRUE){
        if(isset($traverser->data[$traverser->index]));else{
            return new $URLTokenClass($URL, $wsBefore, $pieces, NULL, TRUE);
        }

        if($traverser->eatString(")") !== NULL){
            return new $URLTokenClass($URL, $wsBefore, $pieces, NULL, FALSE);
        }

        $finishTraverser = $traverser->createBranch();
        $wsAfter = $finishTraverser->eatPattern('[' . $whitespaceRegexSet . ']*');
        if($wsAfter !== ""){
            $wsAfter = new $WhitespaceTokenClass($wsAfter);
            if(isset($finishTraverser->data[$finishTraverser->index]));else{
                $traverser->importBranch($finishTraverser);
                return new $URLTokenClass($URL, $wsBefore, $pieces, $wsAfter, TRUE);
            }
            if($finishTraverser->eatString(")") !== NULL){
                $traverser->importBranch($finishTraverser);
                return new $URLTokenClass($URL, $wsBefore, $pieces, $wsAfter, FALSE);
            }
            $remnants = $eatBadURLRemnants($traverser);
            return new $BadURLTokenClass($URL, $wsBefore, $pieces, $remnants);
        }

        if($traverser->createBranch()->eatString("\\") !== NULL){
            $escape = $eatValidEscape($traverser);
            if(isset($escape)){
                $pieces[] = $escape;
                continue;
            }else{
                $remnants = $eatBadURLRemnants($traverser);
                return new $BadURLTokenClass($URL, $wsBefore, $pieces, $remnants);
            }
        }

        if($traverser->createBranch()->eatPattern('[' . $blacklistedCodePointsRegexSet . ']') !== NULL){
            $remnants = $eatBadURLRemnants($traverser);
            return new $BadURLTokenClass($URL, $wsBefore, $pieces, $remnants);
        }

        $piece = $traverser->eatPattern('[^' . $blacklistedCodePointsRegexSet . ']+');
        // This must include everything but the CPs already handled
        // in the previous steps, therefore it can never be empty
        assert(isset($piece));
        $pieces[] = new $URLBitTokenClass($piece);
    }
}
