<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Error;
use Closure;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsBitToken;

function eatBadURLRemnantsToken(
    Traverser $traverser,
    ?Closure $eatEscapeToken = NULL,
    String $BadURLRemnantsTokenClass = BadURLRemnantsToken::CLASS,
    String $BadURLRemnantsBitTokenClass = BadURLRemnantsBitToken::CLASS
): BadURLRemnantsToken{
    if(isset($eatEscapeToken));else{
        $eatEscapeToken = __NAMESPACE__ . "\\eatEscapeToken";
    }
    $pieces = [];
    while(TRUE){
        if(isset($traverser->data[$traverser->index]));else{
            return new $BadURLRemnantsTokenClass($pieces, TRUE);
        }
        if($traverser->eatString(")") !== NULL){
            return new $BadURLRemnantsTokenClass($pieces, FALSE);
        }
        $bit = $traverser->eatPattern('[^\\)\\\\]+'); // var_export(preg_quote(")\\"));
        if(isset($bit)){
            $piece = new $BadURLRemnantsBitTokenClass($bit);
        }else{
            $piece = $eatEscapeToken($traverser);
        }
        if(isset($piece));else{
            throw new Error();
        }
        $pieces[] = $piece;
    }
}
