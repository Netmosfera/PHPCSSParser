<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser\Components;

use Netmosfera\PHPCSSAST\Nodes\Components\FunctionComponent;
use Netmosfera\PHPCSSAST\Parser\Components\TokenStream;
use Netmosfera\PHPCSSAST\Tokens\Names\FunctionToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\RightParenthesisToken;

function eatFunctionComponent(TokenStream $stream): ?FunctionComponent{
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
            return new FunctionComponent($functionToken, $componentValueNodes, TRUE);
        }

        $delimiter = $stream->tokens[$stream->index];
        if($delimiter instanceof RightParenthesisToken){
            $stream->index++;
            return new FunctionComponent($functionToken, $componentValueNodes, FALSE);
        }

        assert(is_int($oldIndex = $stream->index)); // Does nothing - just saves the index
        $componentValueNodes[] = eatComponent($stream);
        assert($stream->index > $oldIndex); // ...so that it can check this.
    }
}
