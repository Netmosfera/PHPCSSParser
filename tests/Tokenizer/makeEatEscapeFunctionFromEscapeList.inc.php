<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSASTDev\assertNotMatch;
use Netmosfera\PHPCSSAST\Tokens\Escapes\Escape;
use Netmosfera\PHPCSSAST\Traverser;
use Error;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function makeEatEscapeFunctionFromEscapeList($escapes){
    return function(Traverser $t) use(&$escapes): ?Escape{
        if(count($escapes) === 0){
            throw new Error();
        }

        $escape = array_shift($escapes);
        /** @var Escape $escape */

        assertNotMatch($t->eatStr((String)$escape), NULL);

        return $escape;
    };
}
