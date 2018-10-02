<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedAtKeywordToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedIdentifierToken;
use function Netmosfera\PHPCSSASTTests\StandardTokenizer\Fakes\eatIdentifierTokenFailingFunction;
use function Netmosfera\PHPCSSASTTests\StandardTokenizer\Fakes\eatIdentifierTokenFunction;
use function Netmosfera\PHPCSSAST\StandardTokenizer\eatAtKeywordToken;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | NULL if not starting with @
 * #2 | NULL if @ is not followed by a valid identifier
 * #3 | token if @ is followed by a valid identifier
 */
class eatAtKeywordTokenTest extends TestCase
{
    public function data1(){
        return cartesianProduct(ANY_UTF8(), ["not-at-keyword", ""]);
    }

    /** @dataProvider data1 */
    public function test1(String $prefix, String $rest){
        $traverser = getTraverser($prefix, $rest);
        $expected = NULL;
        $eatIdentifier = eatIdentifierTokenFailingFunction();
        $actual = eatAtKeywordToken($traverser, $eatIdentifier);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data2(){
        return cartesianProduct(ANY_UTF8(), ["9-not-a-identifier", ""]);
    }

    /** @dataProvider data2 */
    public function test2(String $prefix, String $rest){
        $traverser = getTraverser($prefix, "@" . $rest);
        $expected = NULL;
        $eatIdentifier = eatIdentifierTokenFunction(NULL);
        $actual = eatAtKeywordToken($traverser, $eatIdentifier);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), "@" . $rest);
    }

    public function data3(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8());
    }

    /** @dataProvider data3 */
    public function test3(String $prefix, String $rest){
        $traverser = getTraverser($prefix, "@the-identifier" . $rest);
        $nameBit = new CheckedNameBitToken("the-identifier");
        $name = new CheckedNameToken([$nameBit]);
        $identifier = new CheckedIdentifierToken($name);
        $expected = new CheckedAtKeywordToken($identifier);
        $eatIdentifier = eatIdentifierTokenFunction($identifier);
        $actual = eatAtKeywordToken($traverser, $eatIdentifier);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }
}
