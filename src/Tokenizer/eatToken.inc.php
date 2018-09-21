<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use Netmosfera\PHPCSSAST\Traverser;
use Netmosfera\PHPCSSAST\Tokens\Token;
use Netmosfera\PHPCSSAST\Tokens\Operators\CDOToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\CDCToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\ColonToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\CommaToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\SemicolonToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\DelimiterToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\LeftParenthesisToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\RightParenthesisToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\LeftCurlyBracketToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\LeftSquareBracketToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\RightCurlyBracketToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\RightSquareBracketToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function eatToken(
    Traverser $traverser,
    Closure $eatIdentifierLikeToken,
    Closure $eatWhitespaceToken,
    Closure $eatNumericToken,
    Closure $eatHashToken,
    Closure $eatStringToken,
    Closure $eatAtKeywordToken,
    Closure $eatCommentToken
): ?Token{

    if($traverser->isEOF()){
        return NULL;
    }

    $savePoint = $traverser->savepoint();
    $codePoint = $traverser->eatLength(1);
    if($codePoint === ":"){ return new ColonToken(); }
    if($codePoint === ","){ return new CommaToken(); }
    if($codePoint === "{"){ return new LeftCurlyBracketToken(); }
    if($codePoint === "("){ return new LeftParenthesisToken(); }
    if($codePoint === "["){ return new LeftSquareBracketToken(); }
    if($codePoint === "}"){ return new RightCurlyBracketToken(); }
    if($codePoint === ")"){ return new RightParenthesisToken(); }
    if($codePoint === "]"){ return new RightSquareBracketToken(); }
    if($codePoint === ";"){ return new SemicolonToken(); }
    $traverser->rollback($savePoint);

    if($traverser->eatStr("<!--") !== NULL){
        return new CDOToken();
    }

    if($traverser->eatStr("-->") !== NULL){
        return new CDCToken();
    }

    $token =
        $eatIdentifierLikeToken($traverser) ??
        $eatWhitespaceToken($traverser) ??
        $eatNumericToken($traverser) ??
        $eatHashToken($traverser) ??
        $eatStringToken($traverser) ??
        $eatAtKeywordToken($traverser) ??
        $eatCommentToken($traverser);

    if($token !== NULL){
        return $token;
    }

    return new DelimiterToken($traverser->eatLength(1));
}
