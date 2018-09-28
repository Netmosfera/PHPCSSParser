<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedBadURLRemnantsToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedBadURLRemnantsBitToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function eatBadURLRemnantsToken(
    Traverser $traverser,
    Closure $eatEscape
): CheckedBadURLRemnantsToken{
    $pieces = [];

    for(;;){
        if($traverser->isEOF()){
            return new CheckedBadURLRemnantsToken($pieces, TRUE);
        }

        if($traverser->eatStr(")") !== NULL){
            return new CheckedBadURLRemnantsToken($pieces, FALSE);
        }

        $piece = $traverser->eatExp('[^\\)\\\\]+'); // var_export(preg_quote(")\\"));

        $piece = $piece ?? $eatEscape($traverser);

        if(is_string($piece)){
            $piece = new CheckedBadURLRemnantsBitToken($piece);
        }

        assert($piece !== NULL);

        $pieces[] = $piece;
    }
}
