<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser\Algorithms;

use Netmosfera\PHPCSSAST\Nodes\Components\CurlySimpleBlockComponent;
use Netmosfera\PHPCSSAST\Nodes\MainNodes\InvalidRuleNode;
use Netmosfera\PHPCSSAST\Nodes\MainNodes\QualifiedRuleNode;
use Netmosfera\PHPCSSAST\Parser\ComponentStream;

function eatQualifiedRuleNode(ComponentStream $stream){
    // @TODO assert this cannot start with a whitespace or comment token
    // as these must be consumed separately before this function is called

    $preludePieces = [];
    while(TRUE){
        $indexBeforeWhitespace = $stream->index;
        $whitespaceAmidstPieces = eatOptionalWhitespaceSequence($stream);

        if(isset($stream->components[$stream->index]));else{
            $stream->index = $indexBeforeWhitespace;
            return new InvalidRuleNode($preludePieces);
        }

        foreach($whitespaceAmidstPieces as $whitespacePiece){
            $preludePieces[] = $whitespacePiece;
        }

        $piece = $stream->components[$stream->index];
        $stream->index++;

        if($piece instanceof CurlySimpleBlockComponent){
            return new QualifiedRuleNode($preludePieces, $piece);
        }

        $preludePieces[] = $piece;
    }
}
