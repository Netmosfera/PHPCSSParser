<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser\ComponentValues;

use Netmosfera\PHPCSSAST\Parser\TokenStream;
use Netmosfera\PHPCSSAST\Nodes\ComponentValues\ComponentValueNode;

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

    return $stream->tokens[$stream->index++];
}
