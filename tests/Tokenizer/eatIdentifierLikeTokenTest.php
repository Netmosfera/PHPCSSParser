<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

use IntlChar;
use Netmosfera\PHPCSSAST\Tokens\Escapes\CodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EncodedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\FunctionToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\URLBitToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\URLToken;
use PHPUnit\Framework\TestCase;
use function dechex;
use function Netmosfera\PHPCSSAST\Tokenizer\eatIdentifierLikeToken;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\Tokenizer\Fakes\eatIdentifierTokenFunction;
use function Netmosfera\PHPCSSASTTests\Tokenizer\Fakes\eatURLTokenFailingFunction;
use function Netmosfera\PHPCSSASTTests\Tokenizer\Fakes\eatURLTokenFunction;

/**
 * Tests in this file:
 *
 * #1 | no identifier returns NULL
 * #2 | IdentifierToken if not followed by (
 * #3 | URLToken if url( not followed by string delimiter
 * #4 | FunctionToken if url( followed by string delimiter
 * #5 | FunctionToken otherwise
 */
class eatIdentifierLikeTokenTest extends TestCase
{
    private function URLIdentifiers(){
        $a = function(String $bit){
            return new NameBitToken($bit);
        };

        $b = function(String $cp){
            return new EncodedCodePointEscapeToken($cp);
        };

        $c = function(String $cp){
            $hexDigits = dechex(IntlChar::ord($cp));
            return new CodePointEscapeToken($hexDigits, NULL);
        };

        $names = [];
        $names[] = new NameToken([$a("url")]);
        $names[] = new NameToken([$a("u"), $b("r"), $c("l")]);
        $names[] = new NameToken([$c("u"), $a("r"), $b("l")]);
        $names[] = new NameToken([$b("u"), $c("r"), $a("l")]);

        $urls = [];
        foreach($names as $name){
            $urls[] = new IdentifierToken($name);
        }
        return $urls;
    }

    //------------------------------------------------------------------------------------

    public function data1(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8("@ not name-start cp"));
    }

    /** @dataProvider data1 */
    public function test1(String $prefix, String $rest){
        $identifierLike = NULL;

        $traverser = getTraverser($prefix, $rest);
        $eatIdentifier = eatIdentifierTokenFunction(NULL);
        $eatURL = eatURLTokenFailingFunction();
        $actualIdentifierLike = eatIdentifierLikeToken($traverser, $eatIdentifier, $eatURL);

        assertMatch($actualIdentifierLike, $identifierLike);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data2(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8("@ not name cp"));
    }

    /** @dataProvider data2 */
    public function test2(String $prefix, String $rest){
        $nameBit = new NameBitToken("identifier_name");
        $name = new NameToken([$nameBit]);
        $identifierLike = new IdentifierToken($name);

        $traverser = getTraverser($prefix, $identifierLike . $rest);
        $eatIdentifier = eatIdentifierTokenFunction($identifierLike);
        $eatURL = eatURLTokenFailingFunction();
        $actualIdentifierLike = eatIdentifierLikeToken($traverser, $eatIdentifier, $eatURL);

        assertMatch($actualIdentifierLike, $identifierLike);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data3(){
        return cartesianProduct(
            ANY_UTF8(),
            $this->URLIdentifiers(),
            ANY_UTF8("@ not name code point")
        );
    }

    /** @dataProvider data3 */
    public function test3(String $prefix, IdentifierToken $identifier, String $rest){
        $URLBit = new URLBitToken("works");
        $identifierLike = new URLToken($identifier, NULL, [$URLBit], NULL, FALSE);

        $traverser = getTraverser($prefix, $identifierLike . $rest);
        $eatIdentifier = eatIdentifierTokenFunction($identifier);
        $eatURL = eatURLTokenFunction($identifierLike);
        $actualIdentifierLike = eatIdentifierLikeToken($traverser, $eatIdentifier, $eatURL);

        assertMatch($actualIdentifierLike, $identifierLike);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data4(){
        return cartesianProduct(
            ANY_UTF8(),
            $this->URLIdentifiers(),
            ANY_UTF8("@ not name code point")
        );
    }

    /** @dataProvider data4 */
    public function test4(String $prefix, IdentifierToken $URLIdentifier, String $rest){
        $identifierLike = new FunctionToken($URLIdentifier);

        $traverser = getTraverser($prefix, $identifierLike . $rest);
        $eatIdentifier = eatIdentifierTokenFunction($URLIdentifier);
        $eatURL = eatURLTokenFunction(NULL);
        $actualIdentifierLike = eatIdentifierLikeToken($traverser, $eatIdentifier, $eatURL);

        assertMatch($actualIdentifierLike, $identifierLike);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data5(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8("@not name code point"));
    }

    /** @dataProvider data5 */
    public function test5(String $prefix, String $rest){
        $nameBit = new NameBitToken("func_name");
        $name = new NameToken([$nameBit]);
        $identifier = new IdentifierToken($name);
        $identifierLike = new FunctionToken($identifier);

        $traverser = getTraverser($prefix, $identifierLike . $rest);
        $eatIdentifier = eatIdentifierTokenFunction($identifier);
        $eatURLToken = eatURLTokenFailingFunction();
        $actualIdentifierLike = eatIdentifierLikeToken($traverser, $eatIdentifier, $eatURLToken);

        assertMatch($actualIdentifierLike, $identifierLike);
        assertMatch($traverser->eatAll(), $rest);
    }
}
