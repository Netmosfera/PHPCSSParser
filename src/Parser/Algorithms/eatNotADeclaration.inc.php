<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser\Algorithms;

use Netmosfera\PHPCSSAST\Parser\NodeStream;
use Netmosfera\PHPCSSAST\Tokens\Operators\SemicolonToken;
use Netmosfera\PHPCSSAST\Nodes\Components\InvalidDeclarationNode;

function eatNotADeclaration(NodeStream $stream): InvalidDeclarationNode{
    // @TODO assert this cannot start with a whitespace or comment token
    // as these must be consumed separately before this function is called

    $pieces = [];
    while(TRUE){
        $indexBeforeWhitespace = $stream->index;
        $whitespaceAmidstPieces = eatOptionalWhitespaceSequence($stream);

        if(isset($stream->nodes[$stream->index]));else{
            $stream->index = $indexBeforeWhitespace;
            return new InvalidDeclarationNode($pieces);
        }

        $piece = $stream->nodes[$stream->index];
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
