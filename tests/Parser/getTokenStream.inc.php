<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use Netmosfera\PHPCSSAST\Parser\TokenStream;
use Netmosfera\PHPCSSAST\Tokenizer\StandardTokenizer;

function getTokenStream(Bool $testPrefix, String $css){
    $tokenizer = new StandardTokenizer();
    $prefix = $testPrefix ? " body { background-color: #BADA55; } " : "";
    $prefixTokens = $tokenizer->tokenize($prefix);
    $tokens = $tokenizer->tokenize($prefix . $css);
    $stream = new TokenStream($tokens);
    $stream->index = count($prefixTokens);
    return $stream;
}
