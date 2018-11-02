<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use function Netmosfera\PHPCSSAST\match;
use function Netmosfera\PHPCSSAST\Parser\Components\tokensToComponents;
use Netmosfera\PHPCSSAST\Parser\ComponentStream;

function getTestComponentStream(Bool $testPrefix, String $CSS): ComponentStream{
    $prefix = $testPrefix ? "body{background-color:#BADA55;}" : "";

    $prefixNodes = tokensToComponents(getTestTokens($prefix));

    $nodes = tokensToComponents(getTestTokens($prefix . $CSS));

    assert(match(
        $prefixNodes,
        array_slice($nodes, 0, count($prefixNodes))
    ));

    $stream = new ComponentStream($nodes);
    $stream->index = count($prefixNodes);
    return $stream;
}
