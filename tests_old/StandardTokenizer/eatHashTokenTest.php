<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedHashToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use function Netmosfera\PHPCSSAST\StandardTokenizer\eatHashToken;
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
class eatHashTokenTest extends TestCase
{
    public function data1(){
        return cartesianProduct(ANY_UTF8(), ["@-not-hash", ""]);
    }

    /** @dataProvider data1 */
    public function test1(String $prefix, String $rest){
        $traverser = getTraverser($prefix, $rest);
        $expected = NULL;
        $eatName = function(Traverser $traverser): ?NameToken{
            return NULL;
        };
        $actual = eatHashToken($traverser, $eatName);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data2(){
        return cartesianProduct(ANY_UTF8(), ["@-not-hash", ""]);
    }

    /** @dataProvider data2 */
    public function test2(String $prefix, String $rest){
        $traverser = getTraverser($prefix, "#" . $rest);
        $expected = NULL;
        $eatName = function(Traverser $traverser): ?NameToken{
            return NULL;
        };
        $actual = eatHashToken($traverser, $eatName);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), "#" . $rest);
    }

    public function data3(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8());
    }

    /** @dataProvider data3 */
    public function test3(String $prefix, String $rest){
        $traverser = getTraverser($prefix, "#hash" . $rest);
        $name = new CheckedNameToken([new CheckedNameBitToken("hash")]);
        $expected = new CheckedHashToken($name);
        $eatName = function(Traverser $traverser) use($name): ?NameToken{
            return $traverser->eatStr("hash") === NULL ? NULL : $name;
        };
        $actual = eatHashToken($traverser, $eatName);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }
}
