<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\Strings\CheckedStringToken;
use Netmosfera\PHPCSSAST\TokensChecked\Strings\CheckedBadStringToken;
use function Netmosfera\PHPCSSASTTests\TokensChecked\makeStringPieceAfterPieceFunction;
use function Netmosfera\PHPCSSASTTests\StandardTokenizer\Fakes\eatEscapeTokenFunction;
use function Netmosfera\PHPCSSAST\StandardTokenizer\eatStringToken;
use function Netmosfera\PHPCSSASTTests\makePiecesSample;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | good string
 * #2 | good string terminated with eof
 * #3 | bad string
  */
class eatStringTokenTest extends TestCase
{
    public function data1(){
        return cartesianProduct(
            ANY_UTF8(),
            ["\"", "'"],
            makePiecesSample(makeStringPieceAfterPieceFunction(FALSE)),
            ANY_UTF8()
        );
    }

    /** @dataProvider data1 */
    public function test1(String $prefix, String $delimiter, Array $pieces, String $rest){
        $string = new CheckedStringToken($delimiter, $pieces, FALSE);

        $traverser = getTraverser($prefix, $string . $rest);
        $eatEscape = eatEscapeTokenFunction($pieces);
        $actualString = eatStringToken($traverser, "\f", $eatEscape);

        assertMatch($actualString, $string);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data2(){
        return cartesianProduct(
            ANY_UTF8(),
            ["\"", "'"],
            makePiecesSample(makeStringPieceAfterPieceFunction(TRUE))
        );
    }

    /** @dataProvider data2 */
    public function test2(String $prefix, String $delimiter, Array $pieces){
        $string = new CheckedStringToken($delimiter, $pieces, TRUE);

        $traverser = getTraverser($prefix, $string . "");
        $eatEscape = eatEscapeTokenFunction($pieces);
        $actualString = eatStringToken($traverser, "\f", $eatEscape);

        assertMatch($actualString, $string);
        assertMatch($traverser->eatAll(), "");
    }

    public function data3(){
        return cartesianProduct(
            ANY_UTF8(),
            ["\"", "'"],
            makePiecesSample(makeStringPieceAfterPieceFunction(FALSE)),
            ANY_UTF8()
        );
    }

    /** @dataProvider data3 */
    public function test3(String $prefix, String $delimiter, Array $pieces, String $rest){
        $string = new CheckedBadStringToken($delimiter, $pieces);

        $traverser = getTraverser($prefix, $string . "\f" . $rest);
        $eatEscape = eatEscapeTokenFunction($pieces);
        $actualString = eatStringToken($traverser, "\f", $eatEscape);

        assertMatch($actualString, $string);
        assertMatch($traverser->eatAll(), "\f" . $rest);
    }
}
