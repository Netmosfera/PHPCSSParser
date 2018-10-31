<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use function Netmosfera\PHPCSSAST\match;

function verifyTokens(array $tokens){
    $stringified = implode("", $tokens);
    $controlTokens = (new Tokenizer())->tokenize($stringified)->tokens();
    foreach($tokens as $index => $token){
        $controlToken = $controlTokens[$index];
        if(match($token, $controlToken) === FALSE){
            throw new InvalidTokens($token);
        }
    }
}
