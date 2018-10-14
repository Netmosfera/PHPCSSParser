<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use Error;
use Closure;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedBadURLRemnantsToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedBadURLRemnantsBitToken;

function eatBadURLRemnantsToken(
    Traverser $traverser,
    Closure $eatEscapeToken
): CheckedBadURLRemnantsToken{
    $pieces = [];
    while(TRUE){
        if($traverser->isEOF()){
            return new CheckedBadURLRemnantsToken($pieces, TRUE);
        }
        if($traverser->eatString(")") !== NULL){
            return new CheckedBadURLRemnantsToken($pieces, FALSE);
        }
        $bit = $traverser->eatPattern('[^\\)\\\\]+'); // var_export(preg_quote(")\\"));
        if(isset($bit)){
            $piece = new CheckedBadURLRemnantsBitToken($bit);
        }else{
            $piece = $eatEscapeToken($traverser);
        }
        if(isset($piece) === FALSE){
            throw new Error();
        }
        $pieces[] = $piece;
    }
}
