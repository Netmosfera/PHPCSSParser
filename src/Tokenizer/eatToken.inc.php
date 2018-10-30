<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Token;
use Netmosfera\PHPCSSAST\Tokens\Misc\CDOToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\CDCToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\ColonToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\CommaToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\SemicolonToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\LeftParenthesisToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\RightParenthesisToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\LeftCurlyBracketToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\LeftSquareBracketToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\RightCurlyBracketToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\RightSquareBracketToken;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedDelimiterToken;

function eatToken(
    Traverser $traverser,
    Closure $eatIdentifierLikeToken,
    Closure $eatWhitespaceToken,
    Closure $eatNumericToken,
    Closure $eatHashToken,
    Closure $eatStringToken,
    Closure $eatAtKeywordToken,
    Closure $eatCommentToken,
    ColonToken $colonToken,
    CommaToken $commaToken,
    LeftCurlyBracketToken $leftCurlyBracketToken,
    LeftParenthesisToken $leftParenthesisToken,
    LeftSquareBracketToken $leftSquareBracketToken,
    RightCurlyBracketToken $rightCurlyBracketToken,
    RightParenthesisToken $rightParenthesisToken,
    RightSquareBracketToken $rightSquareBracketToken,
    SemicolonToken $semicolonToken,
    CDOToken $CDOToken,
    CDCToken $CDCToken,
    String $DelimiterTokenClass = CheckedDelimiterToken::CLASS
): ?Token{

    if(isset($traverser->data[$traverser->index]));else{
        return NULL;
    }

    $savePoint = $traverser->index;
    $codePoint = $traverser->eatLength(1);
    if($codePoint === ":"){ return $colonToken; }
    if($codePoint === ","){ return $commaToken; }
    if($codePoint === "{"){ return $leftCurlyBracketToken; }
    if($codePoint === "("){ return $leftParenthesisToken; }
    if($codePoint === "["){ return $leftSquareBracketToken; }
    if($codePoint === "}"){ return $rightCurlyBracketToken; }
    if($codePoint === ")"){ return $rightParenthesisToken; }
    if($codePoint === "]"){ return $rightSquareBracketToken; }
    if($codePoint === ";"){ return $semicolonToken; }
    $traverser->index = $savePoint;

    if($traverser->eatString("-->") !== NULL){
        return $CDCToken;
    }

    $token = $eatIdentifierLikeToken($traverser);
    if(isset($token)){
        return $token;
    }

    $token = $eatWhitespaceToken($traverser);
    if(isset($token)){
        return $token;
    }

    $token = $eatNumericToken($traverser);
    if(isset($token)){
        return $token;
    }

    $token = $eatHashToken($traverser);
    if(isset($token)){
        return $token;
    }

    $token = $eatAtKeywordToken($traverser);
    if(isset($token)){
        return $token;
    }

    $token = $eatStringToken($traverser);
    if(isset($token)){
        return $token;
    }

    $token = $eatCommentToken($traverser);
    if(isset($token)){
        return $token;
    }

    if($traverser->eatString("<!--") !== NULL){
        return $CDOToken;
    }

    return new $DelimiterTokenClass($traverser->eatPattern("."));
}
