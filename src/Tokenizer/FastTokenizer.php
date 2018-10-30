<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\Tokens;
use Netmosfera\PHPCSSAST\Tokens\RootToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\CDOToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\CDCToken;
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
use Netmosfera\PHPCSSAST\Tokens\Operators\ColonToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\CommaToken;
use Netmosfera\PHPCSSAST\Tokens\Numbers\NumericToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\AnyStringToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\BadStringToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EOFEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\URLBitToken;
use Netmosfera\PHPCSSAST\Tokens\Numbers\DimensionToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringBitToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\AnyURLToken;
use Netmosfera\PHPCSSAST\Tokens\Numbers\PercentageToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\SemicolonToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierLikeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\CodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\LeftParenthesisToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\LeftCurlyBracketToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\RightParenthesisToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ContinuationEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\LeftSquareBracketToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\RightCurlyBracketToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\RightSquareBracketToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsBitToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EncodedCodePointEscapeToken;

class FastTokenizer
{
    private $_eatIdentifier;
    private $_eatIdentifierLike;
    private $_eatWhitespace;
    private $_eatNumeric;
    private $_eatHash;
    private $_eatString;
    private $_eatAtKeyword;
    private $_eatComment;
    private $_eatNumber;
    private $_eatName;
    private $_eatBadURLRemnants;
    private $_eatURL;
    private $_eatEscape;
    private $_eatNullEscape;
    private $_eatValidEscape;

    public function __construct(){
        $this->_eatNumber = function(Traverser $traverser): ?NumberToken{
            return eatNumberToken(
                $traverser,
                SpecData::DIGITS_REGEX_SET,
                NumberToken::CLASS
            );
        };

        $this->_eatName = function(Traverser $traverser): ?NameToken{
            return eatNameToken(
                $traverser,
                SpecData::NAME_COMPONENTS_BYTES_REGEX_SET,
                $this->_eatValidEscape,
                NameBitToken::CLASS,
                NameToken::CLASS
            );
        };

        $this->_eatBadURLRemnants = function(
            Traverser $traverser
        ): BadURLRemnantsToken{
            return eatBadURLRemnantsToken(
                $traverser,
                $this->_eatEscape,
                BadURLRemnantsToken::CLASS,
                BadURLRemnantsBitToken::CLASS
            );
        };

        $this->_eatURL = function(
            Traverser $traverser,
            IdentifierToken $URL
        ): ?AnyURLToken{
            return eatURLToken(
                $traverser,
                $URL,
                SpecData::WHITESPACES_REGEX_SET,
                SpecData::URL_TOKEN_BIT_NOT_CPS_REGEX_SET,
                $this->_eatValidEscape,
                $this->_eatBadURLRemnants,
                WhitespaceToken::CLASS,
                URLBitToken::CLASS,
                URLToken::CLASS,
                BadURLToken::CLASS
            );
        };

        $this->_eatEscape = function(Traverser $traverser): ?EscapeToken{
            return
                ($this->_eatValidEscape)($traverser) ??
                ($this->_eatNullEscape)($traverser);
        };

        $this->_eatNullEscape = function(
            Traverser $traverser
        ): ?ValidEscapeToken{
            return eatNullEscapeToken(
                $traverser,
                SpecData::NEWLINES_REGEX_SEQS,
                EOFEscapeToken::CLASS,
                ContinuationEscapeToken::CLASS
            );
        };

        $this->_eatValidEscape = function(
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

        $this->_eatIdentifier = function(
            Traverser $traverser
        ): ?IdentifierToken{
            return eatIdentifierToken(
                $traverser,
                SpecData::NAME_STARTERS_BYTES_REGEX_SET,
                SpecData::NAME_COMPONENTS_BYTES_REGEX_SET,
                $this->_eatValidEscape,
                NameBitToken::CLASS,
                NameToken::CLASS,
                IdentifierToken::CLASS
            );
        };

        $this->_eatIdentifierLike = function(
            Traverser $traverser
        ): ?IdentifierLikeToken{
            return eatIdentifierLikeToken(
                $traverser,
                $this->_eatIdentifier,
                $this->_eatURL,
                FunctionToken::CLASS
            );
        };

        $this->_eatWhitespace = function(
            Traverser $traverser
        ): ?WhitespaceToken{
            return eatWhitespaceToken(
                $traverser,
                SpecData::WHITESPACES_REGEX_SET,
                WhitespaceToken::CLASS
            );
        };

        $this->_eatNumeric = function(Traverser $traverser): ?NumericToken{
            return eatNumericToken(
                $traverser,
                $this->_eatNumber,
                $this->_eatIdentifier,
                PercentageToken::CLASS,
                DimensionToken::CLASS
            );
        };

        $this->_eatHash = function(Traverser $traverser): ?HashToken{
            return eatHashToken(
                $traverser,
                $this->_eatName,
                HashToken::CLASS
            );
        };

        $this->_eatString = function(Traverser $traverser): ?AnyStringToken{
            return eatStringToken(
                $traverser,
                SpecData::NEWLINES_REGEX_SET,
                $this->_eatEscape,
                StringBitToken::CLASS,
                StringToken::CLASS,
                BadStringToken::CLASS
            );
        };

        $this->_eatAtKeyword = function(
            Traverser $traverser
        ): ?AtKeywordToken{
            return eatAtKeywordToken(
                $traverser,
                $this->_eatIdentifier,
                AtKeywordToken::CLASS
            );
        };

        $this->_eatComment = function(Traverser $traverser): ?CommentToken{
            return eatCommentToken(
                $traverser,
                CommentToken::CLASS
            );
        };
    }

    /** @return RootToken[] */
    public function tokenize(String $CSSCode): Tokens{
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
        $CDOToken = new CDOToken();
        $CDCToken = new CDCToken();

        while(isset($traverser->data[$traverser->index])){
            $tokens[] = eatToken(
                $traverser,
                $this->_eatIdentifierLike,
                $this->_eatWhitespace,
                $this->_eatNumeric,
                $this->_eatHash,
                $this->_eatString,
                $this->_eatAtKeyword,
                $this->_eatComment,
                $colonToken,
                $commaToken,
                $leftCurlyBracketToken,
                $leftParenthesisToken,
                $leftSquareBracketToken,
                $rightCurlyBracketToken,
                $rightParenthesisToken,
                $rightSquareBracketToken,
                $semicolonToken,
                $CDOToken,
                $CDCToken,
                DelimiterToken::CLASS
            );
        }

        return new Tokens($tokens);
    }
}
