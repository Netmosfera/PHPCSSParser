<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use Netmosfera\PHPCSSAST\Parser\Components\TokenStream;
use function Netmosfera\PHPCSSAST\match;

function getTestTokenStream(Bool $testPrefix, String $CSS): TokenStream{
    $prefix = $testPrefix ? "body{background-color:#BADA55;}" : "";

    $prefixTokens = getTestTokens($prefix);

    $tokens = getTestTokens($prefix . $CSS);

    assert(match($prefixTokens, array_slice($tokens, 0, count($prefixTokens))));

    $stream = new TokenStream($tokens);
    $stream->index = count($prefixTokens);
    return $stream;
}
