<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser\ComponentValues;

use Netmosfera\PHPCSSAST\Parser\TokenStream;

function tokensToNodes(
    array $tokens
): array{
    $tokenStream = new TokenStream($tokens);
    $nodes = [];
    while(isset($tokenStream->tokens[$tokenStream->index])){
        $nodes[] = eatComponentValue($tokenStream);
    }
    return $nodes;
}
