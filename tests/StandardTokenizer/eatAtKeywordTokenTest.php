<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use Netmosfera\PHPCSSAST\Tokens\Names\AtKeywordToken;
use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use function Netmosfera\PHPCSSAST\StandardTokenizer\eatAtKeywordToken;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | NULL if not starting with @
 * #2 | NULL if @ is not followed by a valid identifier
 * #3 | token if @ is followed by a valid identifier
 */
class eatAtKeywordTokenTest extends TestCase
{
    function data1(){
        return cartesianProduct(ANY_UTF8(), ["not-at", ""]);
    }

    /** @dataProvider data1 */
    function test1(String $prefix, String $rest){
        $traverser = getTraverser($prefix, $rest);
        $expected = NULL;
        $eatIdentifier = function(Traverser $traverser): ?IdentifierToken{
            return NULL;
        };
        $actual = eatAtKeywordToken($traverser, $eatIdentifier);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data2(){
        return cartesianProduct(ANY_UTF8(), ["9-not-a-identifier", ""]);
    }

    /** @dataProvider data2 */
    function test2(String $prefix, String $rest){
        $traverser = getTraverser($prefix, "@" . $rest);
        $expected = NULL;
        $eatIdentifier = function(Traverser $traverser): ?IdentifierToken{
            return NULL;
        };
        $actual = eatAtKeywordToken($traverser, $eatIdentifier);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), "@" . $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data3(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8());
    }

    /** @dataProvider data3 */
    function test3(String $prefix, String $rest){
        $traverser = getTraverser($prefix, "@the-identifier" . $rest);
        $identifier = new IdentifierToken(new NameToken(["the-identifier"]));
        $expected = new AtKeywordToken($identifier);
        $eatIdentifier = function(Traverser $traverser) use($identifier): ?IdentifierToken{
            return $traverser->eatStr("the-identifier") === NULL ? NULL : $identifier;
        };
        $actual = eatAtKeywordToken($traverser, $eatIdentifier);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }
}
