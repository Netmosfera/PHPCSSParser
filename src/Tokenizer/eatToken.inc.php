<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Token;
use Netmosfera\PHPCSSAST\Tokens\Misc\DelimiterToken;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedCDCToken;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedCDOToken;

function eatToken(
    Traverser $traverser,
    Closure $eatIdentifierLikeToken,
    Closure $eatWhitespaceToken,
    Closure $eatNumericToken,
    Closure $eatHashToken,
    Closure $eatStringToken,
    Closure $eatAtKeywordToken,
    Closure $eatCommentToken,
    DelimiterToken $colonToken,
    DelimiterToken $commaToken,
    DelimiterToken $leftCurlyBracketToken,
    DelimiterToken $leftParenthesisToken,
    DelimiterToken $leftSquareBracketToken,
    DelimiterToken $rightCurlyBracketToken,
    DelimiterToken $rightParenthesisToken,
    DelimiterToken $rightSquareBracketToken,
    DelimiterToken $semicolonToken,
    String $DelimiterTokenClass = DelimiterToken::CLASS
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
        return new CheckedCDCToken(); // @TODO as param
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
        return new CheckedCDOToken(); // @TODO as param
    }

    return new $DelimiterTokenClass($traverser->eatPattern("."));
}
