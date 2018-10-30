<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser\Algorithms;

use Netmosfera\PHPCSSAST\Parser\NodeStream;
use Netmosfera\PHPCSSAST\Tokens\Misc\DelimiterToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\Nodes\Components\DeclarationNode;

function eatDeclarationInDeclarationsNode(NodeStream $stream): ?DeclarationNode{
    // @TODO assert this cannot start with a whitespace or comment token
    // as these must be consumed separately before this function is called

    if(isset($stream->nodes[$stream->index]));else{
        return NULL;
    }
    $identifier = $stream->nodes[$stream->index];
    if($identifier instanceof IdentifierToken){
        $beforeAttemptIndex = $stream->index;
        $stream->index++;
    }else{
        return NULL;
    }

    //------------------------------------------------------------------------------------

    $whitespaceBeforeColon = eatOptionalWhitespaceSequence($stream);

    //------------------------------------------------------------------------------------

    if(isset($stream->nodes[$stream->index]));else{
        $stream->index = $beforeAttemptIndex;
        return NULL;
    }
    $colon = $stream->nodes[$stream->index];
    if($colon instanceof DelimiterToken && (String)$colon === ":"){
        $stream->index++;
    }else{
        $stream->index = $beforeAttemptIndex;
        return NULL;
    }

    //------------------------------------------------------------------------------------

    $whitespaceAfterColon = eatOptionalWhitespaceSequence($stream);

    //------------------------------------------------------------------------------------

    $pieces = [];
    while(TRUE){
        $indexBeforeWhitespace = $stream->index;
        $whitespaceAmidstDefinition = eatOptionalWhitespaceSequence($stream);

        if(isset($stream->nodes[$stream->index]));else{
            $stream->index = $indexBeforeWhitespace;
            return new DeclarationNode(
                $identifier,
                $whitespaceBeforeColon,
                $whitespaceAfterColon,
                $pieces
            );
        }

        $piece = $stream->nodes[$stream->index];
        if($piece instanceof DelimiterToken && (String)$piece === ";"){
            $stream->index = $indexBeforeWhitespace;
            return new DeclarationNode(
                $identifier,
                $whitespaceBeforeColon,
                $whitespaceAfterColon,
                $pieces
            );
        }

        foreach($whitespaceAmidstDefinition as $whitespacePiece){
            $pieces[] = $whitespacePiece;
        }

        $pieces[] = $piece;
        $stream->index++;
    }
}
