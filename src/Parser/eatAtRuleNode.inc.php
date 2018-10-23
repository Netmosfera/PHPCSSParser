<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser;

use Netmosfera\PHPCSSAST\Nodes\AtRuleNode;
use Netmosfera\PHPCSSAST\Nodes\SimpleBlockNode;
use Netmosfera\PHPCSSAST\Tokens\Misc\DelimiterToken;
use Netmosfera\PHPCSSAST\Tokens\Names\AtKeywordToken;

function eatAtRuleNode(TokenStream $stream): ?AtRuleNode{
    if(isset($stream->tokens[$stream->index]));else{
        return NULL;
    }

    $atKeyword = $stream->tokens[$stream->index];
    if($atKeyword instanceof AtKeywordToken);else{
        return NULL;
    }
    $stream->index++;

    $preludePieces = [];
    while(TRUE){
        if(isset($stream->tokens[$stream->index]));else{
            return new AtRuleNode($atKeyword, $preludePieces, NULL);
        }

        $semicolon = $stream->tokens[$stream->index];
        if($semicolon instanceof DelimiterToken && (String)$semicolon === ";"){
            $stream->index++;
            return new AtRuleNode($atKeyword, $preludePieces, ";");
        }

        $componentValue = eatComponentValueNode($stream);
        if(
            $componentValue instanceof SimpleBlockNode &&
            $componentValue->openDelimiter() === "{"
        ){
            return new AtRuleNode($atKeyword, $preludePieces, $componentValue);
        }
        $preludePieces[] = $componentValue;
    }
}
