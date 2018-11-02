<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser\Components;

use Netmosfera\PHPCSSAST\Nodes\Components\Component;
use Netmosfera\PHPCSSAST\Parser\Components\TokenStream;

function eatComponent(TokenStream $stream): ?Component{
    if(isset($stream->tokens[$stream->index]));else{
        return NULL;
    }

    $simpleBlock = eatSimpleBlockComponent($stream);
    if(isset($simpleBlock)){
        return $simpleBlock;
    }

    $function = eatFunctionComponent($stream);
    if(isset($function)){
        return $function;
    }

    return $stream->tokens[$stream->index++];
}
