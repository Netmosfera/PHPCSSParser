<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser\Algorithms;

use Netmosfera\PHPCSSAST\Parser\NodeStream;
use Netmosfera\PHPCSSAST\Tokens\Misc\CommentToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;

function eatOptionalWhitespaceSequence(NodeStream $stream){
    $whitespaceSequence = [];
    while(isset($stream->nodes[$stream->index])){
        $token = $stream->nodes[$stream->index];
        if($token instanceof WhitespaceToken || $token instanceof CommentToken){
            $whitespaceSequence[] = $token;
            $stream->index++;
        }else{
            break;
        }
    }
    return $whitespaceSequence;
}

