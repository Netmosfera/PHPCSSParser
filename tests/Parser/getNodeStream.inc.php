<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use function Netmosfera\PHPCSSAST\match;
use function Netmosfera\PHPCSSAST\Parser\ComponentValues\tokensToNodes;
use Netmosfera\PHPCSSAST\Parser\NodeStream;

function getNodeStream(Bool $testPrefix, String $css): NodeStream{
    $prefix = $testPrefix ? "body{background-color:#BADA55;}" : "";

    $prefixNodes = tokensToNodes(getTokens($prefix));

    $nodes = tokensToNodes(getTokens($prefix . $css));

    assert(match(
        $prefixNodes,
        array_slice($nodes, 0, count($prefixNodes))
    ));

    $stream = new NodeStream($nodes);
    $stream->index = count($prefixNodes);
    return $stream;
}
