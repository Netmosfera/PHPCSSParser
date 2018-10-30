<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser\ComponentValues;

use Netmosfera\PHPCSSAST\Nodes\ComponentValues\Nodes;
use Netmosfera\PHPCSSAST\Parser\TokenStream;
use Netmosfera\PHPCSSAST\Tokens\Tokens;

function tokensToNodes(Tokens $tokens, String $nodesFactory = Nodes::CLASS): Nodes{
    $tokenStream = new TokenStream($tokens->tokens());
    $nodes = [];
    while(isset($tokenStream->tokens[$tokenStream->index])){
        $nodes[] = eatComponentValueNode($tokenStream);
    }
    return new $nodesFactory($nodes);
}
