<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser;

use Netmosfera\PHPCSSAST\Nodes\ListOfRulesNode;
use Netmosfera\PHPCSSAST\Tokens\Misc\CDCToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\CDOToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\CommentToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;

function eatListOfRulesNode(TokenStream $stream, Bool $topLevel): ListOfRulesNode{

    $pieces = [];
    while(TRUE){
        if(isset($stream->tokens[$stream->index]));else{
            return new ListOfRulesNode($pieces);
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
            $pieces[] = $token;
            $stream->index++;
            continue;
        }

        $atRule = eatAtRuleNode($stream);

        if(isset($atRule)){
            $pieces[] = $atRule;
            continue;
        }

        $qualifiedRule = eatQualifiedRuleNode($stream);

        if(isset($qualifiedRule)){
            $pieces[] = $qualifiedRule;
            continue;
        }

        throw new \Error();
    }
}
