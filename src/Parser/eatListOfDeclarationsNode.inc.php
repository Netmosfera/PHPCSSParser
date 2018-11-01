<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser;

use function Netmosfera\PHPCSSAST\Parser\Algorithms\eatAtRuleNode;
use function Netmosfera\PHPCSSAST\Parser\Algorithms\eatNotADeclarationNode;
use function Netmosfera\PHPCSSAST\Parser\Algorithms\eatDeclarationInDeclarationsNode;
use Netmosfera\PHPCSSAST\Tokens\Operators\SemicolonToken;
use Netmosfera\PHPCSSAST\Nodes\ListOfDeclarations;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\CommentToken;

function eatListOfDeclarationsNode(array $nodes): ListOfDeclarations{
    $stream = new NodeStream($nodes);

    $list = [];
    while(TRUE){
        if(isset($stream->nodes[$stream->index]));else{
            return new ListOfDeclarations($list);
        }

        $token = $stream->nodes[$stream->index];
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

        $declaration = eatDeclarationInDeclarationsNode($stream);
        if(isset($declaration)){
            $list[] = $declaration;
            continue;
        }

        $list[] = eatNotADeclarationNode($stream);
        continue;
    }
}
