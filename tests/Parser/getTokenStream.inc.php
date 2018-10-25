<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokenizer\CheckedTokenizer;
use Netmosfera\PHPCSSAST\Parser\TokenStream;

function getTokenStream(Bool $testPrefix, String $css){
    $tokenizer = new CheckedTokenizer();

    $prefix = $testPrefix ? "body{background-color:#BADA55;}" : "";

    $prefixTokens = $tokenizer->tokenize($prefix)->tokens();

    $tokens = $tokenizer->tokenize($prefix . $css)->tokens();

    assert(match($prefixTokens, array_slice($tokens, 0, count($prefixTokens))));

    $stream = new TokenStream($tokens);
    $stream->index = count($prefixTokens);
    return $stream;
}
