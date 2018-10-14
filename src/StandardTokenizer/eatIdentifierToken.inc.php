<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedIdentifierToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use Closure;

function eatIdentifierToken(
    Traverser $traverser,
    String $nameStartRegexSet,
    String $nameRegexSet,
    Closure $eatEscapeToken
): ?CheckedIdentifierToken{
    $nscp = $nameStartRegexSet;
    $ncp = $nameRegexSet;

    // (namestartcp|escape)
    // -(namestartcp|escape)
    // --(namecp|escape)

    $startBit = $traverser->eatPattern(
        '-?[' . $nscp . '][' . $ncp . ']*|--[' . $ncp . ']*'
    );

    if(isset($startBit)){
        $pieces = [new CheckedNameBitToken($startBit)];
    }else{
        $startEscapeBranch = $traverser->createBranch();
        $pieces = [];
        if($startEscapeBranch->eatString("-") !== NULL){
            $pieces[] = new CheckedNameBitToken("-");
        }
        $escape = $eatEscapeToken($startEscapeBranch);
        if($escape === NULL){
            return NULL;
        }
        $pieces[] = $escape;
        $traverser->importBranch($startEscapeBranch);
    }

    while(TRUE){
        $bit = $traverser->eatPattern('[' . $ncp . ']+');
        if(isset($bit)){
            $piece = new CheckedNameBitToken($bit);
        }else{
            $piece = $eatEscapeToken($traverser);
        }
        if($piece === NULL){
            return new CheckedIdentifierToken(new CheckedNameToken($pieces));
        }
        $pieces[] = $piece;
    }
}
