<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser;

use Netmosfera\PHPCSSAST\Nodes\ComponentValueNode;
use Netmosfera\PHPCSSAST\Nodes\PreservedTokenNode;

function eatComponentValueNode(TokenStream $stream): ?ComponentValueNode{
    if(isset($stream->tokens[$stream->index]));else{
        return NULL;
    }

    $simpleBlock = eatSimpleBlockNode($stream);
    if(isset($simpleBlock)){
        return $simpleBlock;
    }

    $function = eatFunctionNode($stream);
    if(isset($function)){
        return $function;
    }

    return new PreservedTokenNode($stream->tokens[$stream->index++]);
}
