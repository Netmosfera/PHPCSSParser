<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Token;
use Netmosfera\PHPCSSAST\Tokens\Misc\CDCToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\CDOToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\ColonToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\CommaToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\SemicolonToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\LeftParenthesisToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\RightParenthesisToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\LeftCurlyBracketToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\LeftSquareBracketToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\RightCurlyBracketToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\RightSquareBracketToken;
use Netmosfera\PHPCSSAST\TokensChecked\Operators\CheckedDelimiterToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

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
    SemicolonToken $semicolonToken
): ?Token{

    if($traverser->isEOF()){
        return NULL;
    }

    $savePoint = $traverser->savepoint();
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
    $traverser->rollback($savePoint);

    if($traverser->eatStr("<!--") !== NULL){
        return new CDOToken();
    }

    if($traverser->eatStr("-->") !== NULL){
        return new CDCToken();
    }

    $token = $eatIdentifierLikeToken($traverser);
    if($token !== NULL){ return $token; }

    $token = $eatWhitespaceToken($traverser);
    if($token !== NULL){ return $token; }

    $token = $eatNumericToken($traverser);
    if($token !== NULL){ return $token; }

    $token = $eatHashToken($traverser);
    if($token !== NULL){ return $token; }

    $token = $eatStringToken($traverser);
    if($token !== NULL){ return $token; }

    $token = $eatAtKeywordToken($traverser);
    if($token !== NULL){ return $token; }

    $token = $eatCommentToken($traverser);
    if($token !== NULL){ return $token; }

    return new CheckedDelimiterToken($traverser->eatLength(1));
}
