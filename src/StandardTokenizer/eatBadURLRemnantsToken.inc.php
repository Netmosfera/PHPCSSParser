<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Names\BadURLRemnantsToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Consumes a {@see BadURLRemnantsToken}.
 */
function eatBadURLRemnantsToken(Traverser $traverser, Closure $eatEscape): BadURLRemnantsToken{
    $pieces = [];

    for(;;){
        if($traverser->isEOF()){
            return new BadURLRemnantsToken($pieces, TRUE);
        }

        if($traverser->eatStr(")") !== NULL){
            return new BadURLRemnantsToken($pieces);
        }

        $piece = $traverser->eatExp('[^\\)\\\\]+'); // var_export(preg_quote(")\\"));

        $piece = $piece ?? $eatEscape($traverser);

        assert($piece !== NULL);

        $pieces[] = $piece;
    }
}
