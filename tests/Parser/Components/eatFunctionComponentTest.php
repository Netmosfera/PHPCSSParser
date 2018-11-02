<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser\Components;

use Netmosfera\PHPCSSAST\Nodes\Components\FunctionComponent;
use function Netmosfera\PHPCSSASTTests\Parser\getTestTokenStream;
use function Netmosfera\PHPCSSASTTests\Parser\stringifyTokenStreamRest;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSAST\Parser\Components\eatFunctionComponent;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_CSS;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\Parser\everySeqFromStart;
use function Netmosfera\PHPCSSASTTests\Parser\getTestToken;

/**
 * Tests in this file:
 *
 * #1 | returns NULL if EOF
 * #2 | returns NULL if not a function-token
 * #3 | loop unterminated
 * #4 | loop terminated
 */
class eatFunctionComponentTest extends TestCase
{
    public function data1(){
        return cartesianProduct([FALSE, TRUE]);
    }

    /** @dataProvider data1 */
    public function test1(Bool $testPrefix){
        $function = NULL;

        $stream = getTestTokenStream($testPrefix, "");
        $actualFunction = eatFunctionComponent($stream);

        assertMatch($actualFunction, $function);
        assertMatch(stringifyTokenStreamRest($stream), "");
    }

    public function data2(){
        return cartesianProduct([FALSE, TRUE], ANY_CSS("not starting with function token"));
    }

    /** @dataProvider data2 */
    public function test2(Bool $testPrefix, String $rest){
        $function = NULL;

        $stream = getTestTokenStream($testPrefix, $rest);
        $actualFunction = eatFunctionComponent($stream);

        assertMatch($actualFunction, $function);
        assertMatch(stringifyTokenStreamRest($stream), $rest);
    }

    public function data3(){
        $componentValues[] = getTestToken("foo");
        $componentValues[] = getTestToken("+123%");
        $componentValues[] = getTestToken("bar");
        $componentValues[] = getTestToken("+456%");
        $componentValues[] = getTestToken("qux");
        return cartesianProduct([FALSE, TRUE], everySeqFromStart($componentValues));
    }

    /** @dataProvider data3 */
    public function test3(Bool $testPrefix, array $componentValues){
        $function = new FunctionComponent(getTestToken("foo("), $componentValues, TRUE);

        $stream = getTestTokenStream($testPrefix, $function . "");
        $actualFunction = eatFunctionComponent($stream);

        assertMatch($actualFunction, $function);
        assertMatch(stringifyTokenStreamRest($stream), "");
    }

    public function data4(){
        $componentValues[] = getTestToken("foo");
        $componentValues[] = getTestToken("+123%");
        $componentValues[] = getTestToken("bar");
        $componentValues[] = getTestToken("+456%");
        $componentValues[] = getTestToken("qux");
        return cartesianProduct([FALSE, TRUE], everySeqFromStart($componentValues), ANY_CSS());
    }

    /** @dataProvider data4 */
    public function test4(Bool $testPrefix, array $componentValues, String $rest){
        $function = new FunctionComponent(getTestToken("foo("), $componentValues, FALSE);

        $stream = getTestTokenStream($testPrefix, $function . $rest);
        $actualFunction = eatFunctionComponent($stream);

        assertMatch($actualFunction, $function);
        assertMatch(stringifyTokenStreamRest($stream), $rest);
    }
}
