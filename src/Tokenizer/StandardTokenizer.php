<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\RootToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use Netmosfera\PHPCSSAST\Tokens\Names\HashToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\CommentToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\DelimiterToken;
use Netmosfera\PHPCSSAST\Tokens\Names\FunctionToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\URLToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Numbers\NumberToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use Netmosfera\PHPCSSAST\Tokens\Names\AtKeywordToken;
use Netmosfera\PHPCSSAST\Tokens\Numbers\NumericToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EOFEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\URLBitToken;
use Netmosfera\PHPCSSAST\Tokens\Numbers\DimensionToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringBitToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\AnyURLToken;
use Netmosfera\PHPCSSAST\Tokens\Numbers\PercentageToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierLikeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\CodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ContinuationEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EncodedCodePointEscapeToken;

class StandardTokenizer
{
    private $eatIdentifier;
    private $eatIdentifierLike;
    private $eatWhitespace;
    private $eatNumeric;
    private $eatHash;
    private $eatString;
    private $eatAtKeyword;
    private $eatComment;
    private $eatNumber;
    private $eatName;
    private $eatBadURLRemnants;
    private $eatURL;
    private $eatEscape;
    private $eatNullEscape;
    private $eatValidEscape;

    public function __construct(){
        $this->eatNumber = function(Traverser $traverser): ?NumberToken{
            return eatNumberToken(
                $traverser,
                SpecData::DIGITS_REGEX_SET,
                NumberToken::CLASS
            );
        };

        $this->eatName = function(Traverser $traverser): ?NameToken{
            return eatNameToken(
                $traverser,
                SpecData::NAME_COMPONENTS_BYTES_REGEX_SET,
                $this->eatValidEscape,
                NameBitToken::CLASS,
                NameToken::CLASS
            );
        };

        $this->eatBadURLRemnants = function(
            Traverser $traverser
        ): BadURLRemnantsToken{
            return eatBadURLRemnantsToken(
                $traverser,
                $this->eatEscape,
                BadURLRemnantsToken::CLASS
            );
        };

        $this->eatURL = function(
            Traverser $traverser,
            IdentifierToken $URL
        ): ?AnyURLToken{
            return eatURLToken(
                $traverser,
                $URL,
                SpecData::WHITESPACES_REGEX_SET,
                SpecData::URL_TOKEN_BIT_NOT_CPS_REGEX_SET,
                $this->eatValidEscape,
                $this->eatBadURLRemnants,
                WhitespaceToken::CLASS,
                URLBitToken::CLASS,
                URLToken::CLASS,
                BadURLToken::CLASS
            );
        };

        $this->eatEscape = function(Traverser $traverser): ?EscapeToken{
            return
                ($this->eatValidEscape)($traverser) ??
                ($this->eatNullEscape)($traverser);
        };

        $this->eatNullEscape = function(
            Traverser $traverser
        ): ?ValidEscapeToken{
            return eatNullEscapeToken(
                $traverser,
                SpecData::NEWLINES_REGEX_SEQS,
                EOFEscapeToken::CLASS,
                ContinuationEscapeToken::CLASS
            );
        };

        $this->eatValidEscape = function(
            Traverser $traverser
        ): ?ValidEscapeToken{
            return eatValidEscapeToken(
                $traverser,
                SpecData::HEX_DIGITS_REGEX_SET,
                SpecData::WHITESPACES_REGEX_SEQS,
                WhitespaceToken::CLASS,
                CodePointEscapeToken::CLASS,
                EncodedCodePointEscapeToken::CLASS
            );
        };

        $this->eatIdentifier = function(
            Traverser $traverser
        ): ?IdentifierToken{
            return eatIdentifierToken(
                $traverser,
                SpecData::NAME_STARTERS_BYTES_REGEX_SET,
                SpecData::NAME_COMPONENTS_BYTES_REGEX_SET,
                $this->eatValidEscape,
                NameBitToken::CLASS,
                NameToken::CLASS,
                IdentifierToken::CLASS
            );
        };

        $this->eatIdentifierLike = function(
            Traverser $traverser
        ): ?IdentifierLikeToken{
            return eatIdentifierLikeToken(
                $traverser,
                $this->eatIdentifier,
                $this->eatURL,
                FunctionToken::CLASS
            );
        };

        $this->eatWhitespace = function(
            Traverser $traverser
        ): ?WhitespaceToken{
            return eatWhitespaceToken(
                $traverser,
                SpecData::WHITESPACES_REGEX_SET,
                WhitespaceToken::CLASS
            );
        };

        $this->eatNumeric = function(Traverser $traverser): ?NumericToken{
            return eatNumericToken(
                $traverser,
                $this->eatNumber,
                $this->eatIdentifier,
                PercentageToken::CLASS,
                DimensionToken::CLASS
            );
        };

        $this->eatHash = function(Traverser $traverser): ?HashToken{
            return eatHashToken(
                $traverser,
                $this->eatName,
                HashToken::CLASS
            );
        };

        $this->eatString = function(Traverser $traverser): ?StringToken{
            return eatStringToken(
                $traverser,
                SpecData::NEWLINES_REGEX_SET,
                $this->eatEscape,
                StringBitToken::CLASS
            );
        };

        $this->eatAtKeyword = function(
            Traverser $traverser
        ): ?AtKeywordToken{
            return eatAtKeywordToken(
                $traverser,
                $this->eatIdentifier,
                AtKeywordToken::CLASS
            );
        };

        $this->eatComment = function(Traverser $traverser): ?CommentToken{
            return eatCommentToken(
                $traverser,
                CommentToken::CLASS
            );
        };
    }

    /** @return RootToken[] */
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
