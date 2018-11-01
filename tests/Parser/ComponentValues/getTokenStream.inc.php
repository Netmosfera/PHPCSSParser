<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser\ComponentValues;

use Netmosfera\PHPCSSAST\Parser\TokenStream;
use function Netmosfera\PHPCSSAST\match;
use function Netmosfera\PHPCSSASTTests\Parser\getTokens;

function getTokenStream(Bool $testPrefix, String $css): TokenStream{
    $prefix = $testPrefix ? "body{background-color:#BADA55;}" : "";

    $prefixTokens = getTokens($prefix);

    $tokens = getTokens($prefix . $css);

    assert(match($prefixTokens, array_slice($tokens, 0, count($prefixTokens))));

    $stream = new TokenStream($tokens);
    $stream->index = count($prefixTokens);
    return $stream;
}
