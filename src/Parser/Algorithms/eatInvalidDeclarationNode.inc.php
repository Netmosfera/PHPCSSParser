<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser\Algorithms;

use Netmosfera\PHPCSSAST\Nodes\MainNodes\InvalidDeclarationNode;
use Netmosfera\PHPCSSAST\Parser\ComponentStream;
use Netmosfera\PHPCSSAST\Tokens\Operators\SemicolonToken;

function eatInvalidDeclarationNode(ComponentStream $stream): InvalidDeclarationNode{
    // @TODO assert this cannot start with a whitespace or comment token
    // as these must be consumed separately before this function is called

    $pieces = [];
    while(TRUE){
        $indexBeforeWhitespace = $stream->index;
        $whitespaceAmidstPieces = eatOptionalWhitespaceSequence($stream);

        if(isset($stream->components[$stream->index]));else{
            $stream->index = $indexBeforeWhitespace;
            return new InvalidDeclarationNode($pieces);
        }

        $piece = $stream->components[$stream->index];
        if($piece instanceof SemicolonToken){
            $stream->index = $indexBeforeWhitespace;
            return new InvalidDeclarationNode($pieces);
        }

        foreach($whitespaceAmidstPieces as $whitespacePiece){
            $pieces[] = $whitespacePiece;
        }

        $pieces[] = $piece;
        $stream->index++;
    }
}
