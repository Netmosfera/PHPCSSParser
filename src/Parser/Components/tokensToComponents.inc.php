<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser\Components;

use Netmosfera\PHPCSSAST\Parser\Components\TokenStream;

function tokensToComponents(
    array $tokens
): array{
    $tokenStream = new TokenStream($tokens);
    $nodes = [];
    while(isset($tokenStream->tokens[$tokenStream->index])){
        $nodes[] = eatComponent($tokenStream);
    }
    return $nodes;
}
