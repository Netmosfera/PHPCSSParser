<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser;

use Netmosfera\PHPCSSAST\Nodes\QualifiedRuleNode;
use Netmosfera\PHPCSSAST\Nodes\SimpleBlockNode;

function eatQualifiedRuleNode(TokenStream $stream): QualifiedRuleNode{
    $preludePieces = [];
    while(TRUE){
        if(isset($stream->tokens[$stream->index]));else{
            return new QualifiedRuleNode($preludePieces, NULL);
        }

        $componentValue = eatComponentValueNode($stream);
        if(
            $componentValue instanceof SimpleBlockNode &&
            $componentValue->openDelimiter() === "{"
        ){
            return new QualifiedRuleNode($preludePieces, $componentValue);
        }
        $preludePieces[] = $componentValue;
    }
}
