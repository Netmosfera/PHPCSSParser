<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser\Algorithms;

use Netmosfera\PHPCSSAST\Nodes\MainNodes\DeclarationNode;
use Netmosfera\PHPCSSAST\Parser\ComponentStream;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\ColonToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\SemicolonToken;

function eatDeclarationNode(ComponentStream $stream): ?DeclarationNode{
    // @TODO assert this cannot start with a whitespace or comment token
    // as these must be consumed separately before this function is called

    if(isset($stream->components[$stream->index]));else{
        return NULL;
    }
    $identifier = $stream->components[$stream->index];
    if($identifier instanceof IdentifierToken){
        $beforeAttemptIndex = $stream->index;
        $stream->index++;
    }else{
        return NULL;
    }

    //------------------------------------------------------------------------------------

    $whitespaceBeforeColon = eatOptionalWhitespaceSequence($stream);

    //------------------------------------------------------------------------------------

    if(isset($stream->components[$stream->index]));else{
        $stream->index = $beforeAttemptIndex;
        return NULL;
    }
    $colon = $stream->components[$stream->index];
    if($colon instanceof ColonToken){
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

        if(isset($stream->components[$stream->index]));else{
            $stream->index = $indexBeforeWhitespace;
            return new DeclarationNode(
                $identifier,
                $whitespaceBeforeColon,
                $whitespaceAfterColon,
                $pieces
            );
        }

        $piece = $stream->components[$stream->index];
        if($piece instanceof SemicolonToken){
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
