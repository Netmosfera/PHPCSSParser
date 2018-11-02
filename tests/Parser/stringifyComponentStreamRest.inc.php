<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use Netmosfera\PHPCSSAST\Parser\ComponentStream;

function stringifyComponentStreamRest(ComponentStream $stream){
    $string = "";
    for($i = $stream->index; $i < count($stream->components); $i++){
        $string .= $stream->components[$i];
    }
    return $string;
}
