<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

use Closure;
use function dechex;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\URLToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\assertNotMatch;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSAST\StandardTokenizer\eatIdentifierLikeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedURLBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedIdentifierToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedURLToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedFunctionToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;
use PHPUnit\Framework\TestCase;
use IntlChar;

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
    private function eatURLTokenFailingFunction(): Closure{
        return function(Traverser $traverser): ?IdentifierToken{
            self::fail();
        };
    }

    private function eatURLTokenFunction(?URLToken $URLToken): Closure{
        return function(Traverser $traverser) use($URLToken): ?URLToken{
            if($URLToken === NULL){
                return NULL;
            }else{
                $stringValue = (String)$URLToken;
                return $traverser->eatStr($stringValue) === NULL ? NULL : $URLToken;
            }
        };
    }

    private function eatIdentifierTokenFailingFunction(): Closure{
        return function(Traverser $traverser): ?IdentifierToken{
            self::fail();
        };
    }

    private function eatIdentifierFunction(?IdentifierToken $identifier): Closure{
        return function(Traverser $traverser) use($identifier): ?IdentifierToken{
            if($identifier === NULL){
                return NULL;
            }else{
                $stringValue = (String)$identifier;
                return $traverser->eatStr($stringValue) === NULL ? NULL : $identifier;
            }
        };
    }


    //------------------------------------------------------------------------------------

    public function data1(){
        return cartesianProduct(ANY_UTF8(), ["+33.123", ""]);
    }

    /** @dataProvider data1 */
    public function test1(String $prefix, String $rest){
        $traverser = getTraverser($prefix, $rest);
        $expected = NULL;
        $eatIdentifierToken = function(Traverser $traverser){
            return NULL;
        };
        $eatURLToken = function(Traverser $traverser){
            self::fail();
        };
        $actual = eatIdentifierLikeToken(
            $traverser, $eatIdentifierToken, "\f", $eatURLToken);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data2(){
        return cartesianProduct(ANY_UTF8(), [" whatever", ""]);
    }

    /** @dataProvider data2 */
    public function test2(String $prefix, String $rest){
        $traverser = getTraverser($prefix, "identifier_name" . $rest);
        $expected = new CheckedIdentifierToken(new CheckedNameToken(
            [new CheckedNameBitToken("identifier_name")]));
        $eatIdentifierToken = function(Traverser $traverser) use($expected){
            assertNotMatch($traverser->eatStr("identifier_name"), NULL);
            return $expected;
        };
        $eatURLToken = function(Traverser $traverser){
            self::fail();
        };
        $actual = eatIdentifierLikeToken(
            $traverser, $eatIdentifierToken, "\f", $eatURLToken);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data3(){
        return cartesianProduct(ANY_UTF8(), $this->URLIdentifiers(), ANY_UTF8());
    }

    /** @dataProvider data3 */
    public function test3(String $prefix, IdentifierToken $URLIdentifier, String $rest){
        $traverser = getTraverser($prefix, "url(\f\f\fpath\f\f\f\f)" . $rest);
        $expected = new CheckedURLToken(NULL,
            [new CheckedURLBitToken("works")], NULL, FALSE);
        $eatIdentifierToken = function(Traverser $traverser) use($URLIdentifier){
            assertNotMatch($traverser->eatStr("url"), NULL);
            return $URLIdentifier;
        };
        $eatURLToken = function(Traverser $traverser) use($expected){
            assertNotMatch($traverser->eatStr("\f\f\fpath\f\f\f\f)"), NULL);
            return $expected;
        };
        $actual = eatIdentifierLikeToken(
            $traverser, $eatIdentifierToken, "\f", $eatURLToken);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data4(){
        return cartesianProduct(ANY_UTF8(), $this->URLIdentifiers(), ANY_UTF8());
    }

    /** @dataProvider data4 */
    public function test4(String $prefix, IdentifierToken $URLIdentifier, String $rest){
        $traverser = getTraverser($prefix, "url(\f\f\f'url'\f\f\f\f)" . $rest);
        $expected = new CheckedFunctionToken($URLIdentifier);
        $eatIdentifierToken = function(Traverser $traverser) use($URLIdentifier){
            assertNotMatch($traverser->eatStr("url"), NULL);
            return $URLIdentifier;
        };
        $eatURLToken = function(Traverser $traverser){
            assertNotMatch(
                $traverser->createBranch()->eatStr("\f\f\f'url'\f\f\f\f)"), NULL);
            return NULL;
        };
        $actual = eatIdentifierLikeToken(
            $traverser, $eatIdentifierToken, "\f", $eatURLToken);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), "\f\f\f'url'\f\f\f\f)" . $rest);
    }

    public function data5(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8());
    }

    /** @dataProvider data5 */
    public function test5(String $prefix, String $rest){
        $traverser = getTraverser($prefix, "func_name(" . $rest);
        $expected = new CheckedFunctionToken(new CheckedIdentifierToken(
            new CheckedNameToken([new CheckedNameBitToken("func_name")])));
        $eatIdentifierToken = function(Traverser $t){
            assertMatch($t->eatStr("func_name"), "func_name");
            return new CheckedIdentifierToken(new CheckedNameToken(
                [new CheckedNameBitToken("func_name")]));
        };
        $eatURLToken = function(Traverser $t){
            self::fail();
        };
        $actual = eatIdentifierLikeToken(
            $traverser, $eatIdentifierToken, "\f", $eatURLToken);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function URLIdentifiers(){
        $b = function(String $bit){
            return new CheckedNameBitToken($bit);
        };

        $c = function(String $cp){
            return new CheckedEncodedCodePointEscapeToken($cp);
        };

        $x = function(String $cp){
            $dec = IntlChar::ord($cp);
            $hex = dechex($dec);
            return new CheckedCodePointEscapeToken($hex, NULL);
        };

        $urls[] = new CheckedIdentifierToken(
            new CheckedNameToken([$b("url")]));

        $urls[] = new CheckedIdentifierToken(
            new CheckedNameToken([$b("u"), $c("r"), $b("l")]));
        $urls[] = new CheckedIdentifierToken(
            new CheckedNameToken([$c("u"), $c("r"), $c("l")]));

        $urls[] = new CheckedIdentifierToken(
            new CheckedNameToken([$b("u"), $x("r"), $b("l")]));
        $urls[] = new CheckedIdentifierToken(
            new CheckedNameToken([$x("u"), $x("r"), $x("l")]));

        return $urls;
    }
}
