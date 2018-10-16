<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Numbers\CheckedNumberToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedIdentifierToken;
use Netmosfera\PHPCSSAST\TokensChecked\Numbers\CheckedDimensionToken;
use Netmosfera\PHPCSSAST\TokensChecked\Numbers\CheckedPercentageToken;
use function Netmosfera\PHPCSSASTTests\Tokenizer\Fakes\eatIdentifierTokenFailingFunction;
use function Netmosfera\PHPCSSASTTests\Tokenizer\Fakes\eatIdentifierTokenFunction;
use function Netmosfera\PHPCSSASTTests\Tokenizer\Fakes\eatNumberTokenFunction;
use function Netmosfera\PHPCSSAST\Tokenizer\eatNumericToken;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | not numeric
 * #2 | percentage
 * #3 | dimension
 * #4 | number
 */
class eatNumericTokenTest extends TestCase
{
    public function data1(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8("not starting with a number"));
    }

    /** @dataProvider data1 */
    public function test1(String $prefix, String $rest){
        $numeric = NULL;

        $traverser = getTraverser($prefix, $rest);
        $eatNumber = eatNumberTokenFunction(NULL);
        $eatIdentifier = eatIdentifierTokenFailingFunction();
        $actualNumeric = eatNumericToken($traverser, $eatNumber, $eatIdentifier);

        assertMatch($actualNumeric, $numeric);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data2(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8());
    }

    /** @dataProvider data2 */
    public function test2(String $prefix, String $rest){
        $number = new CheckedNumberToken("+", "2398", "42", "e", "+", "66");
        $numeric = new CheckedPercentageToken($number);

        $traverser = getTraverser($prefix, $numeric . $rest);
        $eatNumber = eatNumberTokenFunction($number);
        $eatIdentifier = eatIdentifierTokenFailingFunction();
        $actualNumeric = eatNumericToken($traverser, $eatNumber, $eatIdentifier);

        assertMatch($actualNumeric, $numeric);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data3(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8("@ not starting with name CP"));
    }

    /** @dataProvider data3 */
    public function test3(String $prefix, String $rest){
        $number = new CheckedNumberToken("+", "2398", "42", "e", "+", "4");
        $nameBit = new CheckedNameBitToken("iau");
        $name = new CheckedNameToken([$nameBit]);
        $identifier = new CheckedIdentifierToken($name);
        $numeric = new CheckedDimensionToken($number, $identifier);

        $traverser = getTraverser($prefix, $numeric . $rest);
        $eatNumber = eatNumberTokenFunction($number);
        $eatIdentifier = eatIdentifierTokenFunction($identifier);
        $actualNumeric = eatNumericToken($traverser, $eatNumber, $eatIdentifier);

        assertMatch($actualNumeric, $numeric);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data4(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8("@ not starting with name CP or %"));
    }

    /** @dataProvider data4 */
    public function test4(String $prefix, String $rest){
        $numeric = new CheckedNumberToken("+", "42", "24", "e", "-", "44");

        $traverser = getTraverser($prefix, $numeric . $rest);
        $eatNumber = eatNumberTokenFunction($numeric);
        $eatIdentifier = eatIdentifierTokenFunction(NULL);
        $actualNumeric = eatNumericToken($traverser, $eatNumber, $eatIdentifier);

        assertMatch($actualNumeric, $numeric);
        assertMatch($traverser->eatAll(), $rest);
    }
}
