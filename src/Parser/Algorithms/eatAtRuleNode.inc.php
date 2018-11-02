<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser\Algorithms;

use Netmosfera\PHPCSSAST\Nodes\Components\CurlySimpleBlockComponent;
use Netmosfera\PHPCSSAST\Nodes\MainNodes\AtRuleNode;
use Netmosfera\PHPCSSAST\Parser\ComponentStream;
use Netmosfera\PHPCSSAST\Tokens\Names\AtKeywordToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\SemicolonToken;

function eatAtRuleNode(ComponentStream $stream): ?AtRuleNode{
    // @TODO assert $stream does not start with whitespace or comment tokens

    if(isset($stream->components[$stream->index]));else{
        return NULL;
    }

    $atKeyword = $stream->components[$stream->index];
    if($atKeyword instanceof AtKeywordToken);else{
        return NULL;
    }
    $stream->index++;

    $preludePieces = [];
    while(TRUE){
        if(isset($stream->components[$stream->index]));else{
            return new AtRuleNode($atKeyword, $preludePieces, NULL);
        }

        $node = $stream->components[$stream->index];
        $stream->index++;

        if($node instanceof SemicolonToken){
            return new AtRuleNode($atKeyword, $preludePieces, $node);
        }

        if($node instanceof CurlySimpleBlockComponent){
            return new AtRuleNode($atKeyword, $preludePieces, $node);
        }

        $preludePieces[] = $node;
    }
}
