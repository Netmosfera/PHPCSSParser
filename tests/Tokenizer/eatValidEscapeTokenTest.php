<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedWhitespaceToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getHexDigitsSet;
use function Netmosfera\PHPCSSAST\Tokenizer\eatValidEscapeToken;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getNewlinesSet;
use function Netmosfera\PHPCSSASTTests\getSampleCodePointsFromRanges;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #0 | NULL if not starting with backslash
 * #1 | NULL if EOF escape
 * #2 | Token if hex digits
 * #3 | Token if code point
 * #4 | NULL if continuation
 */
class eatValidEscapeTokenTest extends TestCase
{
    public function data0(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8("not starting with backslash"));
    }

    /** @dataProvider data0 */
    public function test0(String $prefix, String $rest){
        $escape = NULL;

        $traverser = getTraverser($prefix, $rest);
        $actualEscape = eatValidEscapeToken($traverser, "D", "\t");

        assertMatch($actualEscape, $escape);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data1(){
        return cartesianProduct(ANY_UTF8());
    }

    /** @dataProvider data1 */
    public function test1(String $prefix){
        $escape = NULL;

        $traverser = getTraverser($prefix, "\\");
        $actualEscape = eatValidEscapeToken($traverser, "D", "\t");

        assertMatch($actualEscape, $escape);
        assertMatch($traverser->eatAll(), "\\");
    }

    public function data2(){
        return cartesianProduct(
            ANY_UTF8(),
            ["D", "DD", "DDD", "DDDD", "DDDDD", "DDDDDD"],
            [NULL, new CheckedWhitespaceToken("\t")],
            ANY_UTF8("not starting with a hex digit")
        );
    }

    /** @dataProvider data2 */
    public function test2(String $prefix, String $hexDigits, ?WhitespaceToken $whitespace, String $rest){
        $escape = new CheckedCodePointEscapeToken($hexDigits, $whitespace);

        $traverser = getTraverser($prefix, $escape . $rest);
        $actualEscape = eatValidEscapeToken($traverser, "D", "\t");

        assertMatch($actualEscape, $escape);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data3(){
        $set = new CompressedCodePointSet();
        $set->selectAll();
        $set->removeAll(getHexDigitsSet());
        $set->removeAll(getNewlinesSet());
        return cartesianProduct(
            ANY_UTF8(),
            getSampleCodePointsFromRanges($set),
            ANY_UTF8()
        );
    }

    /** @dataProvider data3 */
    public function test3(String $prefix, String $codePoint, String $rest){
        $escape = new CheckedEncodedCodePointEscapeToken($codePoint);

        $traverser = getTraverser($prefix, $escape . $rest);
        $actualEscape = eatValidEscapeToken($traverser, "D", "\t");

        assertMatch($actualEscape, $escape);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data4(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8());
    }

    /** @dataProvider data4 */
    public function test4(String $prefix, String $rest){
        $escape = NULL;

        $traverser = getTraverser($prefix, "\\" . "\n" . $rest);
        $actualEscape = eatValidEscapeToken($traverser, "D", "\t");

        assertMatch($actualEscape, $escape);
        assertMatch($traverser->eatAll(), "\\" . "\n" . $rest);
    }
}
