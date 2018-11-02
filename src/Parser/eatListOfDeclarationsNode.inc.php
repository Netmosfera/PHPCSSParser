<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser;

use Netmosfera\PHPCSSAST\Nodes\ListOfDeclarations;
use Netmosfera\PHPCSSAST\Tokens\Misc\CommentToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\SemicolonToken;
use function Netmosfera\PHPCSSAST\Parser\Algorithms\eatAtRuleNode;
use function Netmosfera\PHPCSSAST\Parser\Algorithms\eatDeclarationNode;
use function Netmosfera\PHPCSSAST\Parser\Algorithms\eatInvalidDeclarationNode;

function eatListOfDeclarationsNode(array $nodes): ListOfDeclarations{
    $stream = new ComponentStream($nodes);

    $list = [];
    while(TRUE){
        if(isset($stream->components[$stream->index]));else{
            return new ListOfDeclarations($list);
        }

        $token = $stream->components[$stream->index];
        if(
            $token instanceof WhitespaceToken ||
            $token instanceof CommentToken ||
            $token instanceof SemicolonToken
        ){
            $list[] = $token;
            $stream->index++;
            continue;
        }

        $atRule = eatAtRuleNode($stream);
        if(isset($atRule)){
            $list[] = $atRule;
            continue;
        }

        $declaration = eatDeclarationNode($stream);
        if(isset($declaration)){
            $list[] = $declaration;
            continue;
        }

        $list[] = eatInvalidDeclarationNode($stream);
        continue;
    }
}
