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
use Netmosfera\PHPCSSAST\Tokens\Operators\ColonToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\CommaToken;
use Netmosfera\PHPCSSAST\Tokens\Names\AtKeywordToken;
use Netmosfera\PHPCSSAST\Tokens\Numbers\NumericToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\AnyURLToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\SemicolonToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierLikeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\LeftParenthesisToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\RightParenthesisToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\LeftCurlyBracketToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\LeftSquareBracketToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\RightCurlyBracketToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\RightSquareBracketToken;

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

    private $nameStartRegExpSet;
    private $nameRegExpSet;
    private $hexDigitRegExpSet;
    private $whitespaceRegExp;
    private $whitespaceRegexSet;
    private $newlineRegExp;
    private $newlineRegExpSet;
    private $digitRegExpSet;
    private $URLTokenBlacklistedCodePointsRegExpSet;

    private $eatNumberToken;
    private $eatNameToken;
    private $eatBadURLRemnants;
    private $eatURLToken;
    private $eatAnyEscape;
    private $eatNullEscape;
    private $eatValidEscape;

    public function __construct(){
        $this->nameStartRegExpSet = SpecData::NAME_STARTERS_REGEX_SET;
        $this->nameRegExpSet = SpecData::NAME_COMPONENTS_REGEX_SET;
        $this->hexDigitRegExpSet = SpecData::HEX_DIGITS_REGEX_SET;
        $this->whitespaceRegExp = SpecData::WHITESPACES_REGEX_SEQS;
        $this->whitespaceRegexSet = SpecData::WHITESPACES_REGEX_SET;
        $this->newlineRegExp = SpecData::NEWLINES_REGEX_SEQS;
        $this->newlineRegExpSet = SpecData::NEWLINES_REGEX_SET;
        $this->digitRegExpSet = SpecData::DIGITS_REGEX_SET;
        $this->URLTokenBlacklistedCodePointsRegExpSet =
            SpecData::URL_TOKEN_BIT_NOT_CPS_REGEX_SET;

        //----------------------------------------------------------------------

        $this->eatNumberToken = function(Traverser $traverser): ?NumberToken{
            return eatNumberToken($traverser, $this->digitRegExpSet);
        };

        $this->eatNameToken = function(Traverser $traverser): ?NameToken{
            return eatNameToken(
                $traverser,
                $this->nameRegExpSet,
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
                $this->whitespaceRegexSet,
                $this->URLTokenBlacklistedCodePointsRegExpSet,
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
            return eatNullEscapeToken($traverser, $this->newlineRegExp);
        };

        $this->eatValidEscape = function(
            Traverser $traverser
        ): ?ValidEscapeToken{
            return eatValidEscapeToken(
                $traverser,
                $this->hexDigitRegExpSet,
                $this->whitespaceRegExp,
                $this->newlineRegExpSet
            );
        };

        $this->eatIdentifierToken = function(
            Traverser $traverser
        ): ?IdentifierToken{
            return eatIdentifierToken(
                $traverser,
                $this->nameStartRegExpSet,
                $this->nameRegExpSet,
                $this->eatValidEscape
            );
        };

        $this->eatIdentifierLikeToken = function(
            Traverser $traverser
        ): ?IdentifierLikeToken{
            return eatIdentifierLikeToken(
                $traverser,
                $this->eatIdentifierToken,
                $this->whitespaceRegexSet,
                $this->eatURLToken
            );
        };

        $this->eatWhitespaceToken = function(
            Traverser $traverser
        ): ?WhitespaceToken{
            return eatWhitespaceToken($traverser, $this->whitespaceRegexSet);
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
                $this->newlineRegExpSet,
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

    public function tokenize(String $CSSCode): Array{
        $traverser = new Traverser($CSSCode);

        $tokens = [];

        $colonToken = new ColonToken();
        $commaToken = new CommaToken();
        $leftCurlyBracketToken = new LeftCurlyBracketToken();
        $leftParenthesisToken = new LeftParenthesisToken();
        $leftSquareBracketToken = new LeftSquareBracketToken();
        $rightCurlyBracketToken = new RightCurlyBracketToken();
        $rightParenthesisToken = new RightParenthesisToken();
        $rightSquareBracketToken = new RightSquareBracketToken();
        $semicolonToken = new SemicolonToken();

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
