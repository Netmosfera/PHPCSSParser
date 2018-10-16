<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Nodes\ComponentValueNode;
use Netmosfera\PHPCSSAST\Parser\TokenStream;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedIdentifierToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_CSS;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSAST\Parser\eatFunctionNode;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\Parser\Fakes\failingEatComponentValue;
use Netmosfera\PHPCSSAST\Nodes\FunctionNode;
use function Netmosfera\PHPCSSASTTests\Parser\Fakes\fakeEatComponentValue;
use PHPUnit\Framework\TestCase;

/**
 * Tests in this file:
 *
 * #1 | returns NULL if EOF
 * #2 | returns NULL if not a function-token
 * #3 | returns unterminated function if EOF right after the function token
 * #4 | function with no "arguments"
 * #5 | function with 1 component
 * #6 | function with 2 components
 * #7 | function with 3 components
 * #8 | function with 4 components
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
        $eatComponentValue = failingEatComponentValue();
        $actualFunction = eatFunctionNode($stream, $eatComponentValue);

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
        $eatComponentValue = failingEatComponentValue();
        $actualFunction = eatFunctionNode($stream, $eatComponentValue);

        assertMatch($actualFunction, $function);
        assertMatch(stringifyTokens($stream), $rest);
    }

    function data3(){
        return cartesianProduct([FALSE, TRUE]);
    }

    /** @dataProvider data3 */
    function test3(Bool $testPrefix){
        $function = new FunctionNode(getToken("foo("), [], TRUE);

        $stream = getTokenStream($testPrefix, $function . "");
        $eatComponentValue = failingEatComponentValue();
        $actualFunction = eatFunctionNode($stream, $eatComponentValue);

        assertMatch($actualFunction, $function);
        assertMatch(stringifyTokens($stream), "");
    }

    function data4(){
        return cartesianProduct([FALSE, TRUE], ANY_CSS());
    }

    /** @dataProvider data4 */
    function test4(Bool $testPrefix, String $rest){
        $function = new FunctionNode(getToken("foo("), [], FALSE);

        $stream = getTokenStream($testPrefix, $function . $rest);
        $eatComponentValue = failingEatComponentValue();
        $actualFunction = eatFunctionNode($stream, $eatComponentValue);

        assertMatch($actualFunction, $function);
        assertMatch(stringifyTokens($stream), $rest);
    }

    function data5(){
        return cartesianProduct([FALSE, TRUE], ANY_CSS());
    }

    /** @dataProvider data5 */
    function test5(Bool $testPrefix, String $rest){
        $nameBit = new CheckedNameBitToken("function-argument");
        $name = new CheckedNameToken([$nameBit]);
        $identifier = new CheckedIdentifierToken($name);
        $function = new FunctionNode(getToken("foo("), [$identifier], FALSE);

        $stream = getTokenStream($testPrefix, $function . $rest);
        $eatComponentValue = fakeEatComponentValue([$identifier]);
        $actualFunction = eatFunctionNode($stream, $eatComponentValue);

        assertMatch($actualFunction, $function);
        assertMatch(stringifyTokens($stream), $rest);
    }

    function data6(){
        return cartesianProduct([FALSE, TRUE], ANY_CSS());
    }

    /** @dataProvider data6 */
    function test6(Bool $testPrefix, String $rest){
        $componentValues = getTokens("foo-bar+123.55");
        $function = new FunctionNode(getToken("foo("), $componentValues, FALSE);

        $stream = getTokenStream($testPrefix, $function . $rest);
        $eatComponentValue = fakeEatComponentValue($componentValues);
        $actualFunction = eatFunctionNode($stream, $eatComponentValue);

        assertMatch($actualFunction, $function);
        assertMatch(stringifyTokens($stream), $rest);
    }

    function data7(){
        return cartesianProduct([FALSE, TRUE], ANY_CSS());
    }

    /** @dataProvider data7 */
    function test7(Bool $testPrefix, String $rest){
        $componentValues = getTokens("foo-bar +123.55");
        $function = new FunctionNode(getToken("foo("), $componentValues, FALSE);

        $stream = getTokenStream($testPrefix, $function . $rest);
        $eatComponentValue = fakeEatComponentValue($componentValues);
        $actualFunction = eatFunctionNode($stream, $eatComponentValue);

        assertMatch($actualFunction, $function);
        assertMatch(stringifyTokens($stream), $rest);
    }
}
