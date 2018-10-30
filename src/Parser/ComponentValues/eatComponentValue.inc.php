<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser\ComponentValues;

use Netmosfera\PHPCSSAST\Parser\TokenStream;
use Netmosfera\PHPCSSAST\Nodes\ComponentValues\ComponentValue;

function eatComponentValue(TokenStream $stream): ?ComponentValue{
    if(isset($stream->tokens[$stream->index]));else{
        return NULL;
    }

    $simpleBlock = eatSimpleBlockComponentValue($stream);
    if(isset($simpleBlock)){
        return $simpleBlock;
    }

    $function = eatFunctionComponentValue($stream);
    if(isset($function)){
        return $function;
    }

    return $stream->tokens[$stream->index++];
}
