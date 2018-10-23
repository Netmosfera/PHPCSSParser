<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use Netmosfera\PHPCSSAST\Nodes\SimpleBlockNode;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_CSS;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSAST\Parser\eatComponentValueNode;
use Netmosfera\PHPCSSAST\Nodes\PreservedTokenNode;
use Netmosfera\PHPCSSAST\Nodes\FunctionNode;
use PHPUnit\Framework\TestCase;

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
    function data1(){
        return cartesianProduct([FALSE, TRUE]);
    }

    /** @dataProvider data1 */
    function test1(Bool $testPrefix){
        $componentValue = NULL;

        $stream = getTokenStream($testPrefix, "");
        $actualComponentValue = eatComponentValueNode($stream);

        assertMatch($actualComponentValue, $componentValue);
        assertMatch(stringifyTokens($stream), "");
    }

    function data2(){
        return cartesianProduct([FALSE, TRUE], ANY_CSS());
    }

    /** @dataProvider data2 */
    function test2(Bool $testPrefix, String $rest){
        $componentValues = [new PreservedTokenNode(getToken("foo"))];
        $componentValue = new FunctionNode(getToken("foo("), $componentValues, FALSE);

        $stream = getTokenStream($testPrefix, $componentValue . $rest);
        $actualComponentValue = eatComponentValueNode($stream);

        assertMatch($actualComponentValue, $componentValue);
        assertMatch(stringifyTokens($stream), $rest);
    }

    function data3(){
        return cartesianProduct([FALSE, TRUE], ANY_CSS());
    }

    /** @dataProvider data3 */
    function test3(Bool $testPrefix, String $rest){
        $componentValues = [new PreservedTokenNode(getToken("foo"))];
        $componentValue = new SimpleBlockNode("{", $componentValues, FALSE);

        $stream = getTokenStream($testPrefix, $componentValue . $rest);
        $actualComponentValue = eatComponentValueNode($stream);

        assertMatch($actualComponentValue, $componentValue);
        assertMatch(stringifyTokens($stream), $rest);
    }

    function data4(){
        return cartesianProduct([FALSE, TRUE], ANY_CSS(" not starting with identifier"));
    }

    /** @dataProvider data4 */
    function test4(Bool $testPrefix, String $rest){
        $componentValue = new PreservedTokenNode(getToken("123deg"));

        $stream = getTokenStream($testPrefix, $componentValue . $rest);
        $actualComponentValue = eatComponentValueNode($stream);

        assertMatch($actualComponentValue, $componentValue);
        assertMatch(stringifyTokens($stream), $rest);
    }
}
