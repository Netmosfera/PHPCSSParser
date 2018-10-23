<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser;

use Netmosfera\PHPCSSAST\Tokens\Misc\DelimiterToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\Tokens\Token;

function eatDeclarationNode(TokenStream $stream, Token $terminator): TODO{
    if(isset($stream->tokens[$stream->index]));else{
        return NULL;
    }

    $identifier = $stream->tokens[$stream->index];
    if($identifier instanceof IdentifierToken){
        $stream->index++;
    }else{
        return NULL;
    }

    if(isset($stream->tokens[$stream->index]));else{
        return new DeclarationNode($identifier, $EOF = TRUE);
    }

    $whitespaceBeforeColon = $stream->tokens[$stream->index];
    if($whitespaceBeforeColon instanceof WhitespaceToken){
        $stream->index++;
    }else{
        $whitespaceBeforeColon = NULL;
    }

    if(isset($stream->tokens[$stream->index]));else{
        return new DeclarationNode($identifier, $whitespaceBeforeColon, $EOF = TRUE);
    }

    $colon = $stream->tokens[$stream->index];
    if($colon instanceof DelimiterToken && (String)$colon === ":"){
        $stream->index++;
    }else{
        return new DeclarationNode($identifier, $whitespaceBeforeColon, $colon = FALSE, $EOF = FALSE);
    }




}
