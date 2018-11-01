<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use function Netmosfera\PHPCSSAST\match;
use function Netmosfera\PHPCSSAST\Parser\ComponentValues\tokensToComponentValues;
use Netmosfera\PHPCSSAST\Parser\NodeStream;

function getTestNodeStream(Bool $testPrefix, String $css): NodeStream{
    $prefix = $testPrefix ? "body{background-color:#BADA55;}" : "";

    $prefixNodes = tokensToComponentValues(getTokens($prefix));

    $nodes = tokensToComponentValues(getTokens($prefix . $css));

    assert(match(
        $prefixNodes,
        array_slice($nodes, 0, count($prefixNodes))
    ));

    $stream = new NodeStream($nodes);
    $stream->index = count($prefixNodes);
    return $stream;
}
