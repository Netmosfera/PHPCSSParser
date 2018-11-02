<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser;

use Netmosfera\PHPCSSAST\Nodes\ListOfRules;
use Netmosfera\PHPCSSAST\Tokens\Misc\CDCToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\CDOToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\CommentToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use function Netmosfera\PHPCSSAST\Parser\Algorithms\eatAtRuleNode;
use function Netmosfera\PHPCSSAST\Parser\Algorithms\eatQualifiedRuleNode;

function eatListOfRulesNode(ComponentStream $stream, Bool $topLevel): ListOfRules{
    $pieces = [];
    while(TRUE){
        if(isset($stream->components[$stream->index]));else{
            return new ListOfRules($pieces, $topLevel);
        }

        $token = $stream->components[$stream->index];
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

        $pieces[] = eatQualifiedRuleNode($stream);
    }
}
