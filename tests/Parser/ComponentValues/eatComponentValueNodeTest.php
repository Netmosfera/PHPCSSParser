<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser\ComponentValues;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Nodes\ComponentValues\FunctionComponentValue;
use Netmosfera\PHPCSSAST\Nodes\ComponentValues\SimpleBlockComponentValue;
use function Netmosfera\PHPCSSAST\Parser\ComponentValues\eatComponentValueNode;
use function Netmosfera\PHPCSSASTTests\Parser\stringifyTokens;
use function Netmosfera\PHPCSSASTTests\Parser\getTokenStream;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\Parser\getToken;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_CSS;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | returns NULL if EOF
 * #2 | function
 * #3 | block
 * #4 | anything else
 */
class eatComponentValueNodeTest extends TestCase
{
    public function data1(){
        return cartesianProduct([FALSE, TRUE]);
    }

    /** @dataProvider data1 */
    public function test1(Bool $testPrefix){
        $componentValue = NULL;

        $stream = getTokenStream($testPrefix, "");
        $actualComponentValue = eatComponentValueNode($stream);

        assertMatch($actualComponentValue, $componentValue);
        assertMatch(stringifyTokens($stream), "");
    }

    public function data2(){
        return cartesianProduct([FALSE, TRUE], ANY_CSS());
    }

    /** @dataProvider data2 */
    public function test2(Bool $testPrefix, String $rest){
        $componentValues = [getToken("foo")];
        $componentValue = new FunctionComponentValue(getToken("foo("), $componentValues, FALSE);

        $stream = getTokenStream($testPrefix, $componentValue . $rest);
        $actualComponentValue = eatComponentValueNode($stream);

        assertMatch($actualComponentValue, $componentValue);
        assertMatch(stringifyTokens($stream), $rest);
    }

    public function data3(){
        return cartesianProduct([FALSE, TRUE], ANY_CSS());
    }

    /** @dataProvider data3 */
    public function test3(Bool $testPrefix, String $rest){
        $componentValues = [getToken("foo")];
        $componentValue = new SimpleBlockComponentValue("{", $componentValues, FALSE);

        $stream = getTokenStream($testPrefix, $componentValue . $rest);
        $actualComponentValue = eatComponentValueNode($stream);

        assertMatch($actualComponentValue, $componentValue);
        assertMatch(stringifyTokens($stream), $rest);
    }

    public function data4(){
        return cartesianProduct([FALSE, TRUE], ANY_CSS(" not starting with identifier"));
    }

    /** @dataProvider data4 */
    public function test4(Bool $testPrefix, String $rest){
        $componentValue = getToken("123deg");

        $stream = getTokenStream($testPrefix, $componentValue . $rest);
        $actualComponentValue = eatComponentValueNode($stream);

        assertMatch($actualComponentValue, $componentValue);
        assertMatch(stringifyTokens($stream), $rest);
    }
}
