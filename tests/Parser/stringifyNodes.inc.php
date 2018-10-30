<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use Netmosfera\PHPCSSAST\Parser\NodeStream;

function stringifyNodes(NodeStream $stream){
    $stringified = "";
    for($i = $stream->index; $i < count($stream->nodes); $i++){
        $stringified .= $stream->nodes[$i];
    }
    return $stringified;
}
