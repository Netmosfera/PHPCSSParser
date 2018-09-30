<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use Netmosfera\PHPCSSAST\TokensChecked\Numbers\CheckedNumberToken;

function eatNumberToken(
    Traverser $traverser,
    String $digitRegExpSet
): ?CheckedNumberToken{

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

    return new CheckedNumberToken(
        $sign,
        $wholes,
        $decimals,
        $expLetter,
        $expSign,
        $exponent
    );
}
