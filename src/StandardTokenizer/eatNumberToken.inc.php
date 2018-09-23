<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\Numbers\NumberToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Consumes a {@see NumberToken}, if any.
 */
function eatNumberToken(
    Traverser $traverser,
    String $digitRegExpSet
): ?NumberToken{

    $xx = $traverser->createBranch();

    if($xx->eatExp('\+|-') !== NULL){
        if($xx->eatExp('[' . $digitRegExpSet . ']') !== NULL){
            goto EAT_NUMBER;
        }
        if(
            ($xx->eatStr(".")) !== NULL &&
            ($xx->eatExp('[' . $digitRegExpSet . ']')) !== NULL
        ){
            goto EAT_NUMBER;
        }
    }elseif($xx->eatStr(".") !== NULL){
        if($xx->eatExp('[' . $digitRegExpSet . ']') !== NULL){
            goto EAT_NUMBER;
        }
    }elseif($xx->eatExp('[' . $digitRegExpSet . ']') !== NULL){
        goto EAT_NUMBER;
    }
    return NULL;









    EAT_NUMBER:

    $sign = $traverser->eatExp('\+|-') ?? "";

    $wholes = $traverser->eatExp('[' . $digitRegExpSet . ']+') ?? "";

    $dt = $traverser->createBranch();
    if($dt->eatStr(".") !== NULL){
        $decimals = $dt->eatExp('[' . $digitRegExpSet . ']+') ?? "";
        if($decimals !== ""){ $traverser->importBranch($dt); }
    }else{
        $decimals = "";
    }

    $et = $traverser->createBranch();
    $expLetter = $et->eatExp('e|E') ?? "";
    $expSign = $et->eatExp('\+|-') ?? "";
    $exponent = $et->eatExp('[' . $digitRegExpSet . ']+') ?? "";
    if($expLetter !== "" && $exponent !== ""){
        $traverser->importBranch($et);
    }else{
        $expLetter = $expSign = $exponent = "";
    }

    return new NumberToken(
        $sign,
        $wholes,
        $decimals,
        $expLetter,
        $expSign,
        $exponent
    );
}
