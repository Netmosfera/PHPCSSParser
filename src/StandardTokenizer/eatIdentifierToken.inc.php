<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedIdentifierToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use Closure;

function eatIdentifierToken(
    Traverser $traverser,
    String $nameStartRegExpSet,
    String $nameRegExpSet,
    Closure $eatEscapeFunction
): ?CheckedIdentifierToken{
    $nscp = $nameStartRegExpSet;
    $ncp = $nameRegExpSet;

    // (namestartcp|escape)
    // -(namestartcp|escape)
    // --(namecp|escape)

    $identifierStart = $traverser->eatExp(
        '-?[' . $nscp . '][' . $ncp . ']*|--[' . $ncp . ']*'
    );

    if($identifierStart !== NULL){
        $pieces = [new CheckedNameBitToken($identifierStart)];
    }else{
        $tt = $traverser->createBranch();

        $pieces = [];

        if($tt->eatStr("-") !== NULL){
            $pieces[] = new CheckedNameBitToken("-");
        }

        $escape = $eatEscapeFunction($tt);

        if($escape === NULL){
            return NULL;
        }

        $pieces[] = $escape;

        $traverser->importBranch($tt);
    }

    for(;;){
        $piece = $traverser->eatExp('[' . $ncp . ']+') ??
            $eatEscapeFunction($traverser);

        if($piece === NULL){
            return new CheckedIdentifierToken(new CheckedNameToken($pieces));
        }

        if(is_string($piece)){
            $piece = new CheckedNameBitToken($piece);
        }

        $pieces[] = $piece;
    }
}
