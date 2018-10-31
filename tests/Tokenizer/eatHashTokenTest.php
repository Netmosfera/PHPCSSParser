<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

use Netmosfera\PHPCSSAST\Tokens\Names\HashToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSAST\Tokenizer\eatHashToken;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\Tokenizer\Fakes\eatNameTokenFunction;

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
        return cartesianProduct(ANY_UTF8(), ANY_UTF8("not #"));
    }

    /** @dataProvider data1 */
    public function test1(String $prefix, String $rest){
        $hash = NULL;

        $traverser = getTraverser($prefix, $rest);
        $eatName = eatNameTokenFunction(NULL);
        $actualHash = eatHashToken($traverser, $eatName);

        assertMatch($actualHash, $hash);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data2(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8("@ not name code point"));
    }

    /** @dataProvider data2 */
    public function test2(String $prefix, String $rest){
        $hash = NULL;

        $traverser = getTraverser($prefix, "#" . $rest);
        $eatName = eatNameTokenFunction(NULL);
        $actualHash = eatHashToken($traverser, $eatName);

        assertMatch($actualHash, $hash);
        assertMatch($traverser->eatAll(), "#" . $rest);
    }

    public function data3(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8("@ not name code point"));
    }

    /** @dataProvider data3 */
    public function test3(String $prefix, String $rest){
        $nameBit = new NameBitToken("hash");
        $name = new NameToken([$nameBit]);
        $hash = new HashToken($name);

        $traverser = getTraverser($prefix, $hash . $rest);
        $eatName = eatNameTokenFunction($name);
        $actualHash = eatHashToken($traverser, $eatName);

        assertMatch($actualHash, $hash);
        assertMatch($traverser->eatAll(), $rest);
    }
}
