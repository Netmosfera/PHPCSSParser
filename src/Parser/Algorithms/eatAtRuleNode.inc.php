<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser\Algorithms;

use Netmosfera\PHPCSSAST\Nodes\ComponentValues\SimpleBlockNode;
use Netmosfera\PHPCSSAST\Tokens\Names\AtKeywordToken;
use Netmosfera\PHPCSSAST\Nodes\Components\AtRuleNode;
use Netmosfera\PHPCSSAST\Tokens\Misc\DelimiterToken;
use Netmosfera\PHPCSSAST\Parser\NodeStream;

function eatAtRuleNode(NodeStream $stream): ?AtRuleNode{
    // @TODO assert $stream does not start with whitespace or comment tokens

    if(isset($stream->nodes[$stream->index]));else{
        return NULL;
    }

    $atKeyword = $stream->nodes[$stream->index];
    if($atKeyword instanceof AtKeywordToken);else{
        return NULL;
    }
    $stream->index++;

    $preludePieces = [];
    while(TRUE){
        if(isset($stream->nodes[$stream->index]));else{
            return new AtRuleNode($atKeyword, $preludePieces, NULL);
        }

        $node = $stream->nodes[$stream->index];
        $stream->index++;

        if($node instanceof DelimiterToken && (String)$node === ";"){
            return new AtRuleNode($atKeyword, $preludePieces, ";");
        }

        if($node instanceof SimpleBlockNode && $node->openDelimiter() === "{"){
            return new AtRuleNode($atKeyword, $preludePieces, $node);
        }

        $preludePieces[] = $node;
    }
}
