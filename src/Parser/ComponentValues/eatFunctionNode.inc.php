<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser\ComponentValues;

use Netmosfera\PHPCSSAST\Parser\TokenStream;
use Netmosfera\PHPCSSAST\Tokens\Misc\DelimiterToken;
use Netmosfera\PHPCSSAST\Tokens\Names\FunctionToken;
use Netmosfera\PHPCSSAST\Nodes\ComponentValues\FunctionNode;

function eatFunctionNode(TokenStream $stream): ?FunctionNode{
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
            return new FunctionNode($functionToken, $componentValueNodes, TRUE);
        }

        $delimiter = $stream->tokens[$stream->index];
        if($delimiter instanceof DelimiterToken && (String)$delimiter === ")"){
            $stream->index++;
            return new FunctionNode($functionToken, $componentValueNodes, FALSE);
        }

        assert(is_int($oldIndex = $stream->index)); // Does nothing - just saves the index
        $componentValueNodes[] = eatComponentValueNode($stream);
        assert($stream->index > $oldIndex); // ...so that it can check this.
    }
}
