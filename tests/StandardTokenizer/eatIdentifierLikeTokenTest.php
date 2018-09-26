<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function dechex;
use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\URLBitToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\assertNotMatch;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSAST\StandardTokenizer\eatIdentifierLikeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EncodedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;
use Netmosfera\PHPCSSAST\Tokens\Names\FunctionToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\CodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\URLToken;
use PHPUnit\Framework\TestCase;
use IntlChar;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

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
    function data1(){
        return cartesianProduct(ANY_UTF8(), ["+33.123", ""]);
    }

    /** @dataProvider data1 */
    function test1(String $prefix, String $rest){
        $traverser = getTraverser($prefix, $rest);
        $expected = NULL;
        $eatIdentifierToken = function(Traverser $traverser){ return NULL; };
        $eatURLToken = function(Traverser $traverser){ self::fail(); };
        $actual = eatIdentifierLikeToken($traverser, $eatIdentifierToken, "\f", $eatURLToken);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data2(){
        return cartesianProduct(ANY_UTF8(), [" whatever", ""]);
    }

    /** @dataProvider data2 */
    function test2(String $prefix, String $rest){
        $traverser = getTraverser($prefix, "identifier_name" . $rest);
        $expected = new IdentifierToken(new NameToken([new NameBitToken("identifier_name")]));
        $eatIdentifierToken = function(Traverser $traverser) use($expected){
            assertNotMatch($traverser->eatStr("identifier_name"), NULL);
            return $expected;
        };
        $eatURLToken = function(Traverser $traverser){ self::fail(); };
        $actual = eatIdentifierLikeToken($traverser, $eatIdentifierToken, "\f", $eatURLToken);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data3(){
        return cartesianProduct(ANY_UTF8(), $this->URLIdentifiers(), ANY_UTF8());
    }

    /** @dataProvider data3 */
    function test3(String $prefix, IdentifierToken $URLIdentifier, String $rest){
        $traverser = getTraverser($prefix, "url(\f\f\fpath\f\f\f\f)" . $rest);
        $expected = new URLToken(NULL, [new URLBitToken("works")], NULL, FALSE);
        $eatIdentifierToken = function(Traverser $traverser) use($URLIdentifier){
            assertNotMatch($traverser->eatStr("url"), NULL);
            return $URLIdentifier;
        };
        $eatURLToken = function(Traverser $traverser) use($expected){
            assertNotMatch($traverser->eatStr("\f\f\fpath\f\f\f\f)"), NULL);
            return $expected;
        };
        $actual = eatIdentifierLikeToken($traverser, $eatIdentifierToken, "\f", $eatURLToken);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data4(){
        return cartesianProduct(ANY_UTF8(), $this->URLIdentifiers(), ANY_UTF8());
    }

    /** @dataProvider data4 */
    function test4(String $prefix, IdentifierToken $URLIdentifier, String $rest){
        $traverser = getTraverser($prefix, "url(\f\f\f'url'\f\f\f\f)" . $rest);
        $expected = new FunctionToken($URLIdentifier);
        $eatIdentifierToken = function(Traverser $traverser) use($URLIdentifier){
            assertNotMatch($traverser->eatStr("url"), NULL);
            return $URLIdentifier;
        };
        $eatURLToken = function(Traverser $traverser){
            assertNotMatch($traverser->createBranch()->eatStr("\f\f\f'url'\f\f\f\f)"), NULL);
            return NULL;
        };
        $actual = eatIdentifierLikeToken($traverser, $eatIdentifierToken, "\f", $eatURLToken);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), "\f\f\f'url'\f\f\f\f)" . $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data5(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8());
    }

    /** @dataProvider data5 */
    function test5(String $prefix, String $rest){
        $traverser = getTraverser($prefix, "func_name(" . $rest);
        $expected = new FunctionToken(new IdentifierToken(new NameToken([new NameBitToken("func_name")])));
        $eatIdentifierToken = function(Traverser $t){
            assertMatch($t->eatStr("func_name"), "func_name");
            return new IdentifierToken(new NameToken([new NameBitToken("func_name")]));
        };
        $eatURLToken = function(Traverser $t){ self::fail(); };
        $actual = eatIdentifierLikeToken($traverser, $eatIdentifierToken, "\f", $eatURLToken);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function URLIdentifiers(){
        $b = function(String $bit){
            return new NameBitToken($bit);
        };

        $c = function(String $cp){
            return new EncodedCodePointEscapeToken($cp);
        };

        $x = function(String $cp){
            $dec = IntlChar::ord($cp);
            $hex = dechex($dec);
            return new CodePointEscapeToken($hex, NULL);
        };

        $urls[] = new IdentifierToken(new NameToken([$b("url")]));

        $urls[] = new IdentifierToken(new NameToken([$b("u"), $c("r"), $b("l")]));
        $urls[] = new IdentifierToken(new NameToken([$c("u"), $c("r"), $c("l")]));

        $urls[] = new IdentifierToken(new NameToken([$b("u"), $x("r"), $b("l")]));
        $urls[] = new IdentifierToken(new NameToken([$x("u"), $x("r"), $x("l")]));

        return $urls;
    }
}
