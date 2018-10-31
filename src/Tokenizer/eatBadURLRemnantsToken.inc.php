<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Closure;
use Error;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsBitToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsToken;

function eatBadURLRemnantsToken(
    Traverser $traverser,
    Closure $eatEscapeToken
): BadURLRemnantsToken{
    $pieces = [];
    while(TRUE){
        if(isset($traverser->data[$traverser->index]));else{
            return new BadURLRemnantsToken($pieces, TRUE);
        }
        if($traverser->eatString(")") !== NULL){
            return new BadURLRemnantsToken($pieces, FALSE);
        }
        $bit = $traverser->eatPattern('[^\\)\\\\]+'); // var_export(preg_quote(")\\"));
        if(isset($bit)){
            $piece = new BadURLRemnantsBitToken($bit);
        }else{
            $piece = $eatEscapeToken($traverser);
        }
        if(isset($piece));else{
            throw new Error();
        }
        $pieces[] = $piece;
    }
}
