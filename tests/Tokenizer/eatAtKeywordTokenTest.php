<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedAtKeywordToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedIdentifierToken;
use function Netmosfera\PHPCSSASTTests\Tokenizer\Fakes\eatIdentifierTokenFailingFunction;
use function Netmosfera\PHPCSSASTTests\Tokenizer\Fakes\eatIdentifierTokenFunction;
use function Netmosfera\PHPCSSAST\Tokenizer\eatAtKeywordToken;
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
        return cartesianProduct(ANY_UTF8(), ANY_UTF8("not @"));
    }

    /** @dataProvider data1 */
    public function test1(String $prefix, String $rest){
        $atKeyword = NULL;

        $traverser = getTraverser($prefix, $rest);
        $eatIdentifier = eatIdentifierTokenFailingFunction();
        $actualAtKeyword = eatAtKeywordToken($traverser, $eatIdentifier);

        assertMatch($actualAtKeyword, $atKeyword);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data2(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8("9 not name-start cp"));
    }

    /** @dataProvider data2 */
    public function test2(String $prefix, String $rest){
        $atKeyword = NULL;

        $traverser = getTraverser($prefix, "@" . $rest);
        $eatIdentifier = eatIdentifierTokenFunction(NULL);
        $actualAtKeyword = eatAtKeywordToken($traverser, $eatIdentifier);

        assertMatch($actualAtKeyword, $atKeyword);
        assertMatch($traverser->eatAll(), "@" . $rest);
    }

    public function data3(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8("@ not name cp"));
    }

    /** @dataProvider data3 */
    public function test3(String $prefix, String $rest){
        $nameBit = new CheckedNameBitToken("the-identifier");
        $name = new CheckedNameToken([$nameBit]);
        $identifier = new CheckedIdentifierToken($name);
        $atKeyword = new CheckedAtKeywordToken($identifier);

        $traverser = getTraverser($prefix, $atKeyword . $rest);
        $eatIdentifier = eatIdentifierTokenFunction($identifier);
        $actualAtKeyword = eatAtKeywordToken($traverser, $eatIdentifier);

        assertMatch($actualAtKeyword, $atKeyword);
        assertMatch($traverser->eatAll(), $rest);
    }
}
