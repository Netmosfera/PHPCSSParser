<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser\ComponentValues;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Nodes\ComponentValues\FunctionNode;
use function Netmosfera\PHPCSSAST\Parser\ComponentValues\eatFunctionNode;
use function Netmosfera\PHPCSSASTTests\Parser\everySeqFromStart;
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
 * #2 | returns NULL if not a function-token
 * #3 | loop unterminated
 * #4 | loop terminated
 */
class eatFunctionNodeTest extends TestCase
{
    public function data1(){
        return cartesianProduct([FALSE, TRUE]);
    }

    /** @dataProvider data1 */
    public function test1(Bool $testPrefix){
        $function = NULL;

        $stream = getTokenStream($testPrefix, "");
        $actualFunction = eatFunctionNode($stream);

        assertMatch($actualFunction, $function);
        assertMatch(stringifyTokens($stream), "");
    }

    public function data2(){
        return cartesianProduct([FALSE, TRUE], ANY_CSS("not starting with function token"));
    }

    /** @dataProvider data2 */
    public function test2(Bool $testPrefix, String $rest){
        $function = NULL;

        $stream = getTokenStream($testPrefix, $rest);
        $actualFunction = eatFunctionNode($stream);

        assertMatch($actualFunction, $function);
        assertMatch(stringifyTokens($stream), $rest);
    }

    public function data3(){
        $componentValues[] = getToken("foo");
        $componentValues[] = getToken("+123%");
        $componentValues[] = getToken("bar");
        $componentValues[] = getToken("+456%");
        $componentValues[] = getToken("qux");
        return cartesianProduct([FALSE, TRUE], everySeqFromStart($componentValues));
    }

    /** @dataProvider data3 */
    public function test3(Bool $testPrefix, array $componentValues){
        $function = new FunctionNode(getToken("foo("), $componentValues, TRUE);

        $stream = getTokenStream($testPrefix, $function . "");
        $actualFunction = eatFunctionNode($stream);

        assertMatch($actualFunction, $function);
        assertMatch(stringifyTokens($stream), "");
    }

    public function data4(){
        $componentValues[] = getToken("foo");
        $componentValues[] = getToken("+123%");
        $componentValues[] = getToken("bar");
        $componentValues[] = getToken("+456%");
        $componentValues[] = getToken("qux");
        return cartesianProduct([FALSE, TRUE], everySeqFromStart($componentValues), ANY_CSS());
    }

    /** @dataProvider data4 */
    public function test4(Bool $testPrefix, array $componentValues, String $rest){
        $function = new FunctionNode(getToken("foo("), $componentValues, FALSE);

        $stream = getTokenStream($testPrefix, $function . $rest);
        $actualFunction = eatFunctionNode($stream);

        assertMatch($actualFunction, $function);
        assertMatch(stringifyTokens($stream), $rest);
    }
}
