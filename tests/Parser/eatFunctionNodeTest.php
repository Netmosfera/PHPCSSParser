<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_CSS;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSAST\Parser\eatFunctionNode;
use Netmosfera\PHPCSSAST\Nodes\PreservedTokenNode;
use Netmosfera\PHPCSSAST\Nodes\FunctionNode;
use PHPUnit\Framework\TestCase;

/**
 * Tests in this file:
 *
 * #1 | returns NULL if EOF
 * #2 | returns NULL if not a function-token
 * #3 | loop unterminated
 * #4 | loop terminated
 */
class eatFunctionNodeTest extends TestCase
{
    function data1(){
        return cartesianProduct([FALSE, TRUE]);
    }

    /** @dataProvider data1 */
    function test1(Bool $testPrefix){
        $function = NULL;

        $stream = getTokenStream($testPrefix, "");
        $actualFunction = eatFunctionNode($stream);

        assertMatch($actualFunction, $function);
        assertMatch(stringifyTokens($stream), "");
    }

    function data2(){
        return cartesianProduct([FALSE, TRUE], ANY_CSS("not starting with function token"));
    }

    /** @dataProvider data2 */
    function test2(Bool $testPrefix, String $rest){
        $function = NULL;

        $stream = getTokenStream($testPrefix, $rest);
        $actualFunction = eatFunctionNode($stream);

        assertMatch($actualFunction, $function);
        assertMatch(stringifyTokens($stream), $rest);
    }

    function data3(){
        $componentValues[] = new PreservedTokenNode(getToken("foo"));
        $componentValues[] = new PreservedTokenNode(getToken("+123%"));
        $componentValues[] = new PreservedTokenNode(getToken("bar"));
        $componentValues[] = new PreservedTokenNode(getToken("+456%"));
        $componentValues[] = new PreservedTokenNode(getToken("qux"));
        return cartesianProduct([FALSE, TRUE], everySeqFromStart($componentValues));
    }

    /** @dataProvider data3 */
    function test3(Bool $testPrefix, array $componentValues){
        $function = new FunctionNode(getToken("foo("), $componentValues, TRUE);

        $stream = getTokenStream($testPrefix, $function . "");
        $actualFunction = eatFunctionNode($stream);

        assertMatch($actualFunction, $function);
        assertMatch(stringifyTokens($stream), "");
    }

    function data4(){
        $componentValues[] = new PreservedTokenNode(getToken("foo"));
        $componentValues[] = new PreservedTokenNode(getToken("+123%"));
        $componentValues[] = new PreservedTokenNode(getToken("bar"));
        $componentValues[] = new PreservedTokenNode(getToken("+456%"));
        $componentValues[] = new PreservedTokenNode(getToken("qux"));
        return cartesianProduct([FALSE, TRUE], everySeqFromStart($componentValues), ANY_CSS());
    }

    /** @dataProvider data4 */
    function test4(Bool $testPrefix, array $componentValues, String $rest){
        $function = new FunctionNode(getToken("foo("), $componentValues, FALSE);

        $stream = getTokenStream($testPrefix, $function . $rest);
        $actualFunction = eatFunctionNode($stream);

        assertMatch($actualFunction, $function);
        assertMatch(stringifyTokens($stream), $rest);
    }
}
