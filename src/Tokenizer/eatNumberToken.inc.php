<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\Numbers\NumberToken;

function eatNumberToken(
    Traverser $traverser,
    String $digitRegexSet = SpecData::DIGITS_REGEX_SET,
    String $NumberTokenClass = NumberToken::CLASS
): ?NumberToken{

    $matches = $traverser->eatPatterns("
        (?P<sign>[-+]?)
        (?:
            (?P<wholes>[" . $digitRegexSet . "]+)\.(?P<decimals>[" . $digitRegexSet . "]+)|
            (?P<only_wholes>[" . $digitRegexSet . "]+)|
            \.(?P<only_decimals>[" . $digitRegexSet . "]+)
        )
        (?:
            (?P<e_e>[eE])
            (?P<e_sign>[-+]?)
            (?P<e_digits>[" . $digitRegexSet . "]+)
        )?
    ");

    if(isset($matches));else{
        return NULL;
    }

    if(isset($matches["wholes"])){
        $wholes = $matches["wholes"];
        $decimals = $matches["decimals"];
    }elseif(isset($matches["only_wholes"])){
        $wholes = $matches["only_wholes"];
        $decimals = "";
    }else{
        $wholes = "";
        $decimals = $matches["only_decimals"];
    }

    if(isset($matches["e_e"]));else{
        $matches["e_e"] = "";
        $matches["e_sign"] = "";
        $matches["e_digits"] = "";
    }

    return new $NumberTokenClass(
        $matches["sign"],
        $wholes,
        $decimals,
        $matches["e_e"],
        $matches["e_sign"],
        $matches["e_digits"]
    );
}
