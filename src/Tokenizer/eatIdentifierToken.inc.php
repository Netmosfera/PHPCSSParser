<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;

function eatIdentifierToken(
    Traverser $traverser,
    String $nameStartRegexSet,
    String $nameRegexSet,
    Closure $eatEscapeToken
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
        $pieces = [new NameBitToken($startBit)];
    }else{
        $startEscapeBranch = $traverser->createBranch();
        $pieces = [];
        if($startEscapeBranch->eatString("-") !== NULL){
            $pieces[] = new NameBitToken("-");
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
            $piece = new NameBitToken($bit);
        }else{
            $piece = $eatEscapeToken($traverser);
        }
        if(isset($piece));else{
            return new IdentifierToken(new NameToken($pieces));
        }
        $pieces[] = $piece;
    }
}
