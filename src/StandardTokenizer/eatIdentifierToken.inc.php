<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function eatIdentifierToken(
    Traverser $traverser,
    String $nameStartRegExpSet,
    String $nameRegExpSet,
    Closure $eatEscapeFunction
): ?IdentifierToken{
    $nscp = $nameStartRegExpSet;
    $ncp = $nameRegExpSet;

    // (namestartcp|escape)
    // -(namestartcp|escape)
    // --(namecp|escape)

    $identifierStart = $traverser->eatExp('-?[' . $nscp . '][' . $ncp . ']*|--[' . $ncp . ']*');

    if($identifierStart !== NULL){
        $pieces = [new NameBitToken($identifierStart)];
    }else{
        $tt = $traverser->createBranch();

        $pieces = [];

        if($tt->eatStr("-") !== NULL){
            $pieces[] = new NameBitToken("-");
        }

        $escape = $eatEscapeFunction($tt);

        if($escape === NULL){
            return NULL;
        }

        $pieces[] = $escape;

        $traverser->importBranch($tt);
    }

    for(;;){
        $piece = $traverser->eatExp('[' . $ncp . ']+') ?? $eatEscapeFunction($traverser);

        if($piece === NULL){
            return new IdentifierToken(new NameToken($pieces));
        }

        if(is_string($piece)){
            $piece = new NameBitToken($piece);
        }

        $pieces[] = $piece;
    }
}
