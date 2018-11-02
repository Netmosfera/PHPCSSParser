<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser\Algorithms;

use Netmosfera\PHPCSSAST\Parser\ComponentStream;
use Netmosfera\PHPCSSAST\Tokens\Misc\CommentToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;

function eatOptionalWhitespaceSequence(ComponentStream $stream){
    $whitespaceSequence = [];
    while(isset($stream->components[$stream->index])){
        $token = $stream->components[$stream->index];
        if($token instanceof WhitespaceToken || $token instanceof CommentToken){
            $whitespaceSequence[] = $token;
            $stream->index++;
        }else{
            break;
        }
    }
    return $whitespaceSequence;
}

