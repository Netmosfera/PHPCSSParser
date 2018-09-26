<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Escapes\Escape;
use Netmosfera\PHPCSSAST\Tokens\Escapes\HexEscape;
use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;
use Netmosfera\PHPCSSAST\Tokens\Escapes\CodePointEscape;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsToken;
use function Netmosfera\PHPCSSAST\StandardTokenizer\eatBadURLRemnantsToken;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertNotMatch;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1  | immediate EOF
 * #2  | immediate )
 * #3  | immediate non-escape
 * #4  | immediate escape
 * #5  | loop terminated
 * #6  | loop unterminated (EOF)
  */
class eatBadURLRemnantsTokenTest extends TestCase
{
    function data1(){
        return cartesianProduct(ANY_UTF8());
    }

    /** @dataProvider data1 */
    function test1(String $prefix){
        $traverser = getTraverser($prefix, "");
        $eatEscape = function(Traverser $traverser): ?Escape{ return NULL; };
        $actual = eatBadURLRemnantsToken($traverser, $eatEscape);
        $expected = new BadURLRemnantsToken([], TRUE);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), "");
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data2(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8());
    }

    /** @dataProvider data2 */
    function test2(String $prefix, String $rest){
        $traverser = getTraverser($prefix, ")" . $rest);
        $expected = new BadURLRemnantsToken([], FALSE);
        $eatEscape = function(Traverser $traverser): ?Escape{ return NULL; };
        $actual = eatBadURLRemnantsToken($traverser, $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data3(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8());
    }

    /** @dataProvider data3 */
    function test3(String $prefix, String $rest){
        $traverser = getTraverser($prefix, $this->remnants . ")" . $rest);
        $expected = new BadURLRemnantsToken([$this->remnants], FALSE);
        $eatEscape = function(Traverser $traverser): ?Escape{ return NULL; };
        $actual = eatBadURLRemnantsToken($traverser, $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data4(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8());
    }

    /** @dataProvider data4 */
    function test4(String $prefix, String $rest){
        $traverser = getTraverser($prefix, "\\FFAACC" . ")" . $rest);
        $expected = new BadURLRemnantsToken([new HexEscape("FFAACC", NULL)], FALSE);
        $eatEscape = function(Traverser $traverser): ?Escape{
            assertNotMatch($traverser->eatStr("\\FFAACC"), NULL);
            return new HexEscape("FFAACC", NULL);
        };
        $actual = eatBadURLRemnantsToken($traverser, $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data56(){
        return cartesianProduct(
            ANY_UTF8(),
            makePiecesSample(Closure::fromCallable([$this, "getPieces"])),
            ANY_UTF8()
        );
    }

    /** @dataProvider data56 */
    function test5(String $prefix, Array $pieces, String $rest){
        $traverser = getTraverser($prefix, implode("", $pieces) . ")" . $rest);
        $expected = new BadURLRemnantsToken($pieces, FALSE);
        $eatEscape = function(Traverser $traverser): ?Escape{
            $escape = $traverser->eatStr("\\@");
            return $escape === NULL ? NULL : new CodePointEscape("@");
        };
        $actual = eatBadURLRemnantsToken($traverser, $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    /** @dataProvider data56 */
    function test6(String $prefix, Array $pieces){
        $traverser = getTraverser($prefix, implode("", $pieces));
        $expected = new BadURLRemnantsToken($pieces, TRUE);
        $eatEscape = function(Traverser $traverser): ?Escape{
            $escape = $traverser->eatStr("\\@");
            return $escape === NULL ? NULL : new CodePointEscape("@");
        };
        $actual = eatBadURLRemnantsToken($traverser, $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), "");
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function getPieces($afterPiece){
        if(!is_string($afterPiece)){
            $data[] = $this->remnants;
        }
        $data[] = new CodePointEscape("@");
        return $data;
    }

    private $remnants = "bad { } \u{2764} \" URL \u{2764} ' remnants ( url(";
}
