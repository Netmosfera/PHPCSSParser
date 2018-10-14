<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use Netmosfera\PHPCSSAST\TokensChecked\Numbers\CheckedNumberToken;

function eatNumberToken(
    Traverser $traverser,
    String $digitRegexSet
): ?CheckedNumberToken{

    $xx = $traverser->createBranch();

    if($xx->eatPattern('\+|-') !== NULL){
        if($xx->eatPattern('[' . $digitRegexSet . ']') !== NULL){
            goto EAT_NUMBER;
        }
        if(
            ($xx->eatString(".")) !== NULL &&
            ($xx->eatPattern('[' . $digitRegexSet . ']')) !== NULL
        ){
            goto EAT_NUMBER;
        }
    }elseif($xx->eatString(".") !== NULL){
        if($xx->eatPattern('[' . $digitRegexSet . ']') !== NULL){
            goto EAT_NUMBER;
        }
    }elseif($xx->eatPattern('[' . $digitRegexSet . ']') !== NULL){
        goto EAT_NUMBER;
    }
    return NULL;









    EAT_NUMBER:

    $sign = $traverser->eatPattern('\+|-') ?? "";

    $wholes = $traverser->eatPattern('[' . $digitRegexSet . ']+') ?? "";

    $dt = $traverser->createBranch();
    if($dt->eatString(".") !== NULL){
        $decimals = $dt->eatPattern('[' . $digitRegexSet . ']+') ?? "";
        if($decimals !== ""){ $traverser->importBranch($dt); }
    }else{
        $decimals = "";
    }

    $et = $traverser->createBranch();
    $expLetter = $et->eatPattern('e|E') ?? "";
    $expSign = $et->eatPattern('\+|-') ?? "";
    $exponent = $et->eatPattern('[' . $digitRegexSet . ']+') ?? "";
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
