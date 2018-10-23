<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser;

use Netmosfera\PHPCSSAST\Nodes\SimpleBlockNode;
use Netmosfera\PHPCSSAST\Tokens\Misc\DelimiterToken;

function eatSimpleBlockNode(TokenStream $stream): ?SimpleBlockNode{
    if(isset($stream->tokens[$stream->index]));else{
        return NULL;
    }

    $openDelimiter = $stream->tokens[$stream->index];
    if($openDelimiter instanceof DelimiterToken);else{
        return NULL;
    }

    $stringifiedOpenDelimiter = (String)$openDelimiter;
    $delimiters = ["(" => ")", "[" => "]", "{" => "}"];
    if(isset($delimiters[$stringifiedOpenDelimiter]));else{
        return NULL;
    }
    $stringifiedCloseDelimiter = $delimiters[$stringifiedOpenDelimiter];

    $stream->index++;

    $componentValueNodes = [];

    while(TRUE){
        if(isset($stream->tokens[$stream->index]));else{
            return new SimpleBlockNode($stringifiedOpenDelimiter, $componentValueNodes, TRUE);
        }

        $closeDelimiter = $stream->tokens[$stream->index];
        if(
            $closeDelimiter instanceof DelimiterToken &&
            (String)$closeDelimiter === $stringifiedCloseDelimiter
        ){
            $stream->index++;
            return new SimpleBlockNode($stringifiedOpenDelimiter, $componentValueNodes, FALSE);
        }

        assert(is_int($oldIndex = $stream->index)); // Does nothing - just saves the index
        $componentValueNodes[] = eatComponentValueNode($stream);
        assert($stream->index > $oldIndex); // ...so that it can check this.
    }
}
