<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser;

use Netmosfera\PHPCSSAST\Tokens\Tokens;
use Netmosfera\PHPCSSAST\Tokens\Misc\CDCToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\CDOToken;
use Netmosfera\PHPCSSAST\Nodes\ListOfRulesNode;
use Netmosfera\PHPCSSAST\Tokens\Misc\CommentToken;
use Netmosfera\PHPCSSAST\Nodes\PreservedTokenNode;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;

function eatListOfRulesNode(Tokens $tokens, Bool $topLevel): ListOfRulesNode{
    $stream = new TokenStream($tokens->tokens());

    $pieces = [];
    while(TRUE){
        if(isset($stream->tokens[$stream->index]));else{
            return new ListOfRulesNode($pieces, $topLevel);
        }

        $token = $stream->tokens[$stream->index];
        if(
            $token instanceof WhitespaceToken ||
            $token instanceof CommentToken || (
                $topLevel && (
                    $token instanceof CDOToken ||
                    $token instanceof CDCToken
                )
            )
        ){
            $pieces[] = new PreservedTokenNode($token);
            $stream->index++;
            continue;
        }

        $atRule = eatAtRuleNode($stream);
        if(isset($atRule)){
            $pieces[] = $atRule;
            continue;
        }

        $pieces[] = eatQualifiedRuleNode($stream);
    }
}
