<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedIdentifierToken;

function eatIdentifierToken(
    Traverser $traverser,
    String $nameStartRegexSet,
    String $nameRegexSet,
    Closure $eatEscapeToken,
    String $NameBitTokenClass = CheckedNameBitToken::CLASS,
    String $NameTokenClass = CheckedNameToken::CLASS,
    String $IdentifierTokenClass = CheckedIdentifierToken::CLASS
): ?IdentifierToken{
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
        $escape = $eatEscapeToken($startEscapeBranch);
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
            $piece = $eatEscapeToken($traverser);
        }
        if(isset($piece));else{
            return new $IdentifierTokenClass(new $NameTokenClass($pieces));
        }
        $pieces[] = $piece;
    }
}
