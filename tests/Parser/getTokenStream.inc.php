<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Parser\TokenStream;
use Netmosfera\PHPCSSAST\Tokenizer\StandardTokenizer;

function getTokenStream(Bool $testPrefix, String $css){
    $tokenizer = new StandardTokenizer();

    $prefix = $testPrefix ? "body{background-color:#BADA55;}" : "";

    $prefixTokens = $tokenizer->tokenize($prefix);

    $tokens = $tokenizer->tokenize($prefix . $css);

    assert(match($prefixTokens, array_slice($tokens, 0, count($prefixTokens))));

    $stream = new TokenStream($tokens);
    $stream->index = count($prefixTokens);
    return $stream;
}
