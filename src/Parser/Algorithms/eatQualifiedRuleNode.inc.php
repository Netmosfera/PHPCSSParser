<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser\Algorithms;

use Netmosfera\PHPCSSAST\Nodes\Components\InvalidRuleNode;
use Netmosfera\PHPCSSAST\Nodes\Components\QualifiedRuleNode;
use Netmosfera\PHPCSSAST\Nodes\ComponentValues\SimpleBlockComponentValue;
use Netmosfera\PHPCSSAST\Parser\NodeStream;

function eatQualifiedRuleNode(NodeStream $stream){
    // @TODO assert this cannot start with a whitespace or comment token
    // as these must be consumed separately before this function is called

    $preludePieces = [];
    while(TRUE){
        $indexBeforeWhitespace = $stream->index;
        $whitespaceAmidstPieces = eatOptionalWhitespaceSequence($stream);

        if(isset($stream->nodes[$stream->index]));else{
            $stream->index = $indexBeforeWhitespace;
            return new InvalidRuleNode($preludePieces);
        }

        foreach($whitespaceAmidstPieces as $whitespacePiece){
            $preludePieces[] = $whitespacePiece;
        }

        $piece = $stream->nodes[$stream->index];
        $stream->index++;

        if($piece instanceof SimpleBlockComponentValue && $piece->openDelimiter() === "{"){
            return new QualifiedRuleNode($preludePieces, $piece);
        }

        $preludePieces[] = $piece;
    }
}
