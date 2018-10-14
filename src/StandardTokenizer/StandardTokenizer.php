<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\Names\HashToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\CommentToken;
use Netmosfera\PHPCSSAST\Tokens\Numbers\NumberToken;
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
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedDelimiterToken;

class StandardTokenizer
{
    private $eatIdentifierToken;
    private $eatIdentifierLikeToken;
    private $eatWhitespaceToken;
    private $eatNumericToken;
    private $eatHashToken;
    private $eatStringToken;
    private $eatAtKeywordToken;
    private $eatCommentToken;

    private $eatNumberToken;
    private $eatNameToken;
    private $eatBadURLRemnants;
    private $eatURLToken;
    private $eatAnyEscape;
    private $eatNullEscape;
    private $eatValidEscape;

    public function __construct(){
        $this->eatNumberToken = function(Traverser $traverser): ?NumberToken{
            return eatNumberToken($traverser, SpecData::DIGITS_REGEX_SET);
        };

        $this->eatNameToken = function(Traverser $traverser): ?NameToken{
            return eatNameToken(
                $traverser,
                SpecData::NAME_COMPONENTS_BYTES_REGEX_SET,
                $this->eatValidEscape
            );
        };

        $this->eatBadURLRemnants = function(
            Traverser $traverser
        ): BadURLRemnantsToken{
            return eatBadURLRemnantsToken($traverser, $this->eatAnyEscape);
        };

        $this->eatURLToken = function(
            Traverser $traverser,
            IdentifierToken $URL
        ): ?AnyURLToken{
            return eatURLToken(
                $traverser,
                $URL,
                SpecData::WHITESPACES_REGEX_SET,
                SpecData::URL_TOKEN_BIT_NOT_CPS_REGEX_SET,
                $this->eatValidEscape,
                $this->eatBadURLRemnants
            );
        };

        $this->eatAnyEscape = function(Traverser $traverser): ?EscapeToken{
            return
                ($this->eatValidEscape)($traverser) ??
                ($this->eatNullEscape)($traverser);
        };

        $this->eatNullEscape = function(
            Traverser $traverser
        ): ?ValidEscapeToken{
            return eatNullEscapeToken($traverser, SpecData::NEWLINES_REGEX_SEQS);
        };

        $this->eatValidEscape = function(
            Traverser $traverser
        ): ?ValidEscapeToken{
            return eatValidEscapeToken(
                $traverser,
                SpecData::HEX_DIGITS_REGEX_SET,
                SpecData::WHITESPACES_REGEX_SEQS,
                SpecData::NEWLINES_REGEX_SET
            );
        };

        $this->eatIdentifierToken = function(
            Traverser $traverser
        ): ?IdentifierToken{
            return eatIdentifierToken(
                $traverser,
                SpecData::NAME_STARTERS_BYTES_REGEX_SET,
                SpecData::NAME_COMPONENTS_BYTES_REGEX_SET,
                $this->eatValidEscape
            );
        };

        $this->eatIdentifierLikeToken = function(
            Traverser $traverser
        ): ?IdentifierLikeToken{
            return eatIdentifierLikeToken(
                $traverser,
                $this->eatIdentifierToken,
                $this->eatURLToken
            );
        };

        $this->eatWhitespaceToken = function(
            Traverser $traverser
        ): ?WhitespaceToken{
            return eatWhitespaceToken($traverser, SpecData::WHITESPACES_REGEX_SET);
        };

        $this->eatNumericToken = function(Traverser $traverser): ?NumericToken{
            return eatNumericToken(
                $traverser,
                $this->eatNumberToken,
                $this->eatIdentifierToken
            );
        };

        $this->eatHashToken = function(Traverser $traverser): ?HashToken{
            return eatHashToken($traverser, $this->eatNameToken);
        };

        $this->eatStringToken = function(Traverser $traverser): ?StringToken{
            return eatStringToken(
                $traverser,
                SpecData::NEWLINES_REGEX_SET,
                $this->eatAnyEscape
            );
        };

        $this->eatAtKeywordToken = function(
            Traverser $traverser
        ): ?AtKeywordToken{
            return eatAtKeywordToken($traverser, $this->eatIdentifierToken);
        };

        $this->eatCommentToken = function(Traverser $traverser): ?CommentToken{
            return eatCommentToken($traverser);
        };
    }

    public function tokenize(String $CSSCode): array{
        $traverser = new Traverser($CSSCode);

        $tokens = [];

        $colonToken = new CheckedDelimiterToken(":");
        $commaToken = new CheckedDelimiterToken(",");
        $leftCurlyBracketToken = new CheckedDelimiterToken("{");
        $leftParenthesisToken = new CheckedDelimiterToken("(");
        $leftSquareBracketToken = new CheckedDelimiterToken("[");
        $rightCurlyBracketToken = new CheckedDelimiterToken("}");
        $rightParenthesisToken = new CheckedDelimiterToken(")");
        $rightSquareBracketToken = new CheckedDelimiterToken("]");
        $semicolonToken = new CheckedDelimiterToken(";");

        while($traverser->isEOF() === FALSE){
            $tokens[] = eatToken(
                $traverser,
                $this->eatIdentifierLikeToken,
                $this->eatWhitespaceToken,
                $this->eatNumericToken,
                $this->eatHashToken,
                $this->eatStringToken,
                $this->eatAtKeywordToken,
                $this->eatCommentToken,
                $colonToken,
                $commaToken,
                $leftCurlyBracketToken,
                $leftParenthesisToken,
                $leftSquareBracketToken,
                $rightCurlyBracketToken,
                $rightParenthesisToken,
                $rightSquareBracketToken,
                $semicolonToken
            );
        }

        return $tokens;
    }
}
