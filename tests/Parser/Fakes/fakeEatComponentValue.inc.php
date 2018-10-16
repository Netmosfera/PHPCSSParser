<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser\Fakes;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Parser\TokenStream;
use Error;

function fakeEatComponentValue(array $componentValues){
    return function(TokenStream $stream) use($componentValues){
        foreach($componentValues as $componentValue){
            if(match($stream->tokens[$stream->index], $componentValue)){
                $stream->index++;
                return $componentValue;
            }
        }
        throw new Error();
    };
}
