<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use Netmosfera\PHPCSSAST\Parser\Components\TokenStream;

function stringifyTokenStreamRest(TokenStream $stream){
    $string = "";
    for($i = $stream->index; $i < count($stream->tokens); $i++){
        $string .= $stream->tokens[$i];
    }
    return $string;
}
