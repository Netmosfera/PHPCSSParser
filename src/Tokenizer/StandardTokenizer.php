<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Closure;
use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\Escapes\CodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ContinuationEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EncodedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EOFEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\DelimiterToken;
use Netmosfera\PHPCSSAST\Tokens\Names\FunctionToken;
use Netmosfera\PHPCSSAST\Tokens\Names\HashToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\CommentToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\URLBitToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\URLToken;
use Netmosfera\PHPCSSAST\Tokens\Numbers\DimensionToken;
use Netmosfera\PHPCSSAST\Tokens\Numbers\NumberToken;
use Netmosfera\PHPCSSAST\Tokens\Numbers\PercentageToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringBitToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\AtKeywordToken;
use Netmosfera\PHPCSSAST\Tokens\Numbers\NumericToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\AnyURLToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierLikeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsToken;

class StandardTokenizer
{
    private $eatIdentifierLike;
    private $eatWhitespace;
    private $eatNumeric;
    private $eatHash;
    private $eatString;
    private $eatAtKeyword;
    private $eatComment;

    public function __construct(){
        $this->eatIdentifierLike = Closure::fromCallable("Netmosfera\\PHPCSSAST\\Tokenizer\\eatIdentifierLikeToken");
        $this->eatWhitespace = Closure::fromCallable("Netmosfera\\PHPCSSAST\\Tokenizer\\eatWhitespaceToken");
        $this->eatNumeric = Closure::fromCallable("Netmosfera\\PHPCSSAST\\Tokenizer\\eatNumericToken");
        $this->eatHash = Closure::fromCallable("Netmosfera\\PHPCSSAST\\Tokenizer\\eatHashToken");
        $this->eatString = Closure::fromCallable("Netmosfera\\PHPCSSAST\\Tokenizer\\eatStringToken");
        $this->eatAtKeyword = Closure::fromCallable("Netmosfera\\PHPCSSAST\\Tokenizer\\eatAtKeywordToken");
        $this->eatComment = Closure::fromCallable("Netmosfera\\PHPCSSAST\\Tokenizer\\eatCommentToken");
    }

    public function tokenize(String $CSSCode): array{
        $traverser = new Traverser($CSSCode);

        $tokens = [];

        $colonToken = new DelimiterToken(":");
        $commaToken = new DelimiterToken(",");
        $leftCurlyBracketToken = new DelimiterToken("{");
        $leftParenthesisToken = new DelimiterToken("(");
        $leftSquareBracketToken = new DelimiterToken("[");
        $rightCurlyBracketToken = new DelimiterToken("}");
        $rightParenthesisToken = new DelimiterToken(")");
        $rightSquareBracketToken = new DelimiterToken("]");
        $semicolonToken = new DelimiterToken(";");

        while(isset($traverser->data[$traverser->index])){
            $tokens[] = eatToken(
                $traverser,
                $this->eatIdentifierLike,
                $this->eatWhitespace,
                $this->eatNumeric,
                $this->eatHash,
                $this->eatString,
                $this->eatAtKeyword,
                $this->eatComment,
                $colonToken,
                $commaToken,
                $leftCurlyBracketToken,
                $leftParenthesisToken,
                $leftSquareBracketToken,
                $rightCurlyBracketToken,
                $rightParenthesisToken,
                $rightSquareBracketToken,
                $semicolonToken,
                DelimiterToken::CLASS
            );
        }

        return $tokens;
    }
}
