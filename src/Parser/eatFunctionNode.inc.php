<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser;

use Closure;
use Netmosfera\PHPCSSAST\Nodes\FunctionNode;
use Netmosfera\PHPCSSAST\Tokens\Misc\DelimiterToken;
use Netmosfera\PHPCSSAST\Tokens\Names\FunctionToken;

function eatFunctionNode(
    TokenStream $stream,
    Closure $eatComponentValueNode
): ?FunctionNode{
    if(isset($stream->tokens[$stream->index]));else{
        return NULL;
    }
    if($stream->tokens[$stream->index] instanceof FunctionToken);else{
        return NULL;
    }
    $functionToken = $stream->tokens[$stream->index];

    /** @var FunctionToken $functionToken */

    $componentValueNodes = [];

    $stream->index++;

    while(TRUE){
        if(isset($stream->tokens[$stream->index]));else{
            return new FunctionNode($functionToken, $componentValueNodes, TRUE);
        }

        $delimiter = $stream->tokens[$stream->index];
        if($delimiter instanceof DelimiterToken && (String)$delimiter === ")"){
            $stream->index++;
            return new FunctionNode($functionToken, $componentValueNodes, FALSE);
        }

        assert(is_int($oldIndex = $stream->index)); // Does nothing - just saves the index

        $componentValueNodes[] = $eatComponentValueNode($stream);

        assert($stream->index > $oldIndex); // ...so that it can check this
    }
}
