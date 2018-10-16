<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Error;
use Closure;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedBadURLRemnantsToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedBadURLRemnantsBitToken;

function eatBadURLRemnantsToken(
    Traverser $traverser,
    Closure $eatEscapeToken,
    String $BadURLRemnantsTokenClass = CheckedBadURLRemnantsToken::CLASS
): BadURLRemnantsToken{
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
            $piece = new CheckedBadURLRemnantsBitToken($bit);
        }else{
            $piece = $eatEscapeToken($traverser);
        }
        if(isset($piece));else{
            throw new Error();
        }
        $pieces[] = $piece;
    }
}
