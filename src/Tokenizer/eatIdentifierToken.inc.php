<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Closure;
use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;

function eatIdentifierToken(
    Traverser $traverser,
    String $nameStartRegexSet = SpecData::NAME_STARTERS_BYTES_REGEX_SET,
    String $nameRegexSet = SpecData::NAME_COMPONENTS_BYTES_REGEX_SET,
    ?Closure $eatValidEscapeToken = NULL,
    String $NameBitTokenClass = NameBitToken::CLASS,
    String $NameTokenClass = NameToken::CLASS,
    String $IdentifierTokenClass = IdentifierToken::CLASS
): ?IdentifierToken{
    if(isset($eatValidEscapeToken));else{
        $eatValidEscapeToken = __NAMESPACE__ . "\\eatValidEscapeToken";
    }

    $nscp = $nameStartRegexSet;
    $ncp = $nameRegexSet;

    // (namestartcp|escape)
    // -(namestartcp|escape)
    // --(namecp|escape)

    $startBit = $traverser->eatPattern(
        '-?[' . $nscp . '][' . $ncp . ']*|--[' . $ncp . ']*'
    );

    if(isset($startBit)){
        $pieces = [new $NameBitTokenClass($startBit)];
    }else{
        $startEscapeBranch = $traverser->createBranch();
        $pieces = [];
        if($startEscapeBranch->eatString("-") !== NULL){
            $pieces[] = new $NameBitTokenClass("-");
        }
        $escape = $eatValidEscapeToken($startEscapeBranch);
        if(isset($escape));else{
            return NULL;
        }
        $pieces[] = $escape;
        $traverser->importBranch($startEscapeBranch);
    }

    while(TRUE){
        $bit = $traverser->eatPattern('[' . $ncp . ']+');
        if(isset($bit)){
            $piece = new $NameBitTokenClass($bit);
        }else{
            $piece = $eatValidEscapeToken($traverser);
        }
        if(isset($piece));else{
            return new $IdentifierTokenClass(new $NameTokenClass($pieces));
        }
        $pieces[] = $piece;
    }
}
