<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser\ComponentValues;

use Netmosfera\PHPCSSAST\Nodes\ComponentValues\SimpleBlockComponentValue;
use Netmosfera\PHPCSSAST\Parser\TokenStream;
use Netmosfera\PHPCSSAST\Tokens\Operators\LeftCurlyBracketToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\LeftParenthesisToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\LeftSquareBracketToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\RightCurlyBracketToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\RightParenthesisToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\RightSquareBracketToken;

function eatSimpleBlockComponentValue(TokenStream $stream): ?SimpleBlockComponentValue{
    if(isset($stream->tokens[$stream->index]));else{
        return NULL;
    }

    $openDelimiter = $stream->tokens[$stream->index];
    if(
        $openDelimiter instanceof LeftParenthesisToken ||
        $openDelimiter instanceof LeftSquareBracketToken ||
        $openDelimiter instanceof LeftCurlyBracketToken
    );else{
        return NULL;
    }

    $stream->index++;

    $componentValueNodes = [];

    if($openDelimiter instanceof LeftParenthesisToken){
        $mirrorClass = RightParenthesisToken::CLASS;
    }elseif($openDelimiter instanceof LeftSquareBracketToken){
        $mirrorClass = RightSquareBracketToken::CLASS;
    }else{
        $mirrorClass = RightCurlyBracketToken::CLASS;
    }

    while(TRUE){
        if(isset($stream->tokens[$stream->index]));else{
            return new SimpleBlockComponentValue((String)$openDelimiter, $componentValueNodes, TRUE);
        }

        $closeDelimiter = $stream->tokens[$stream->index];
        if($closeDelimiter instanceof $mirrorClass){
            $stream->index++;
            return new SimpleBlockComponentValue((String)$openDelimiter, $componentValueNodes, FALSE);
        }

        assert(is_int($oldIndex = $stream->index)); // Does nothing - just saves the index
        $componentValueNodes[] = eatComponentValue($stream);
        assert($stream->index > $oldIndex); // ...so that it can check this.
    }
}
