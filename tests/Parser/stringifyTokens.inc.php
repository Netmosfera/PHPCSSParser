<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use Netmosfera\PHPCSSAST\Parser\TokenStream;

function stringifyTokens(TokenStream $stream){
    $stringified = "";
    for($i = $stream->index; $i < count($stream->tokens); $i++){
        $stringified .= $stream->tokens[$i];
    }
    return $stringified;
}
