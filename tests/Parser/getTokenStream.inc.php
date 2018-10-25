<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokenizer\FastTokenizer;
use Netmosfera\PHPCSSAST\Parser\TokenStream;
use Netmosfera\PHPCSSAST\Tokens\Tokens;

function getTokenStream(Bool $testPrefix, String $css){
    $tokenizer = new FastTokenizer(function(array $tokens): Tokens{
        return new Tokens($tokens);
    });

    $prefix = $testPrefix ? "body{background-color:#BADA55;}" : "";

    $prefixTokens = $tokenizer->tokenize($prefix)->tokens();

    $tokens = $tokenizer->tokenize($prefix . $css)->tokens();

    assert(match($prefixTokens, array_slice($tokens, 0, count($prefixTokens))));

    $stream = new TokenStream($tokens);
    $stream->index = count($prefixTokens);
    return $stream;
}
