<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

use Netmosfera\PHPCSSAST\Tokens\Strings\BadStringToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringToken;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSAST\Tokenizer\eatStringToken;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\makePiecesSample;
use function Netmosfera\PHPCSSASTTests\Tokenizer\Fakes\eatEscapeTokenFunction;

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
    public function test1(String $prefix, String $delimiter, array $pieces, String $rest){
        $string = new StringToken($delimiter, $pieces, FALSE);

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
    public function test2(String $prefix, String $delimiter, array $pieces){
        $string = new StringToken($delimiter, $pieces, TRUE);

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
    public function test3(String $prefix, String $delimiter, array $pieces, String $rest){
        $string = new BadStringToken($delimiter, $pieces);

        $traverser = getTraverser($prefix, $string . "\f" . $rest);
        $eatEscape = eatEscapeTokenFunction($pieces);
        $actualString = eatStringToken($traverser, "\f", $eatEscape);

        assertMatch($actualString, $string);
        assertMatch($traverser->eatAll(), "\f" . $rest);
    }
}
