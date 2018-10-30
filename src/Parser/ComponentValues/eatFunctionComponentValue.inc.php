<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser\ComponentValues;

use Netmosfera\PHPCSSAST\Parser\TokenStream;
use Netmosfera\PHPCSSAST\Tokens\Names\FunctionToken;
use Netmosfera\PHPCSSAST\Nodes\ComponentValues\FunctionComponentValue;
use Netmosfera\PHPCSSAST\Tokens\Operators\RightParenthesisToken;

function eatFunctionComponentValue(TokenStream $stream): ?FunctionComponentValue{
    if(isset($stream->tokens[$stream->index]));else{
        return NULL;
    }
    $functionToken = $stream->tokens[$stream->index];
    if($functionToken instanceof FunctionToken);else{
        return NULL;
    }
    $stream->index++;

    $componentValueNodes = [];
    while(TRUE){
        if(isset($stream->tokens[$stream->index]));else{
            return new FunctionComponentValue($functionToken, $componentValueNodes, TRUE);
        }

        $delimiter = $stream->tokens[$stream->index];
        if($delimiter instanceof RightParenthesisToken){
            $stream->index++;
            return new FunctionComponentValue($functionToken, $componentValueNodes, FALSE);
        }

        assert(is_int($oldIndex = $stream->index)); // Does nothing - just saves the index
        $componentValueNodes[] = eatComponentValue($stream);
        assert($stream->index > $oldIndex); // ...so that it can check this.
    }
}
