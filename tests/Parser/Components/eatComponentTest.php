<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser\Components;

use Netmosfera\PHPCSSAST\Nodes\Components\CurlySimpleBlockComponent;
use Netmosfera\PHPCSSAST\Nodes\Components\FunctionComponent;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSAST\Parser\Components\eatComponent;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_CSS;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\Parser\getTestToken;
use function Netmosfera\PHPCSSASTTests\Parser\getTestTokenStream;
use function Netmosfera\PHPCSSASTTests\Parser\stringifyTokenStreamRest;

/**
 * Tests in this file:
 *
 * #1 | returns NULL if EOF
 * #2 | function
 * #3 | block
 * #4 | anything else
 */
class eatComponentTest extends TestCase
{
    public function data1(){
        return cartesianProduct([FALSE, TRUE]);
    }

    /** @dataProvider data1 */
    public function test1(Bool $testPrefix){
        $componentValue = NULL;

        $stream = getTestTokenStream($testPrefix, "");
        $actualComponentValue = eatComponent($stream);

        assertMatch($actualComponentValue, $componentValue);
        assertMatch(stringifyTokenStreamRest($stream), "");
    }

    public function data2(){
        return cartesianProduct([FALSE, TRUE], ANY_CSS());
    }

    /** @dataProvider data2 */
    public function test2(Bool $testPrefix, String $rest){
        $componentValues = [getTestToken("foo")];
        $componentValue = new FunctionComponent(
            getTestToken("foo("),
            $componentValues,
            FALSE
        );

        $stream = getTestTokenStream($testPrefix, $componentValue . $rest);
        $actualComponentValue = eatComponent($stream);

        assertMatch($actualComponentValue, $componentValue);
        assertMatch(stringifyTokenStreamRest($stream), $rest);
    }

    public function data3(){
        return cartesianProduct([FALSE, TRUE], ANY_CSS());
    }

    /** @dataProvider data3 */
    public function test3(Bool $testPrefix, String $rest){
        $componentValues = [getTestToken("foo")];
        $componentValue = new CurlySimpleBlockComponent($componentValues, FALSE);

        $stream = getTestTokenStream($testPrefix, $componentValue . $rest);
        $actualComponentValue = eatComponent($stream);

        assertMatch($actualComponentValue, $componentValue);
        assertMatch(stringifyTokenStreamRest($stream), $rest);
    }

    public function data4(){
        return cartesianProduct([FALSE, TRUE], ANY_CSS(" not starting with identifier"));
    }

    /** @dataProvider data4 */
    public function test4(Bool $testPrefix, String $rest){
        $componentValue = getTestToken("123deg");

        $stream = getTestTokenStream($testPrefix, $componentValue . $rest);
        $actualComponentValue = eatComponent($stream);

        assertMatch($actualComponentValue, $componentValue);
        assertMatch(stringifyTokenStreamRest($stream), $rest);
    }
}
