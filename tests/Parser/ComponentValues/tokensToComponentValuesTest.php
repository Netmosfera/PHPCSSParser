<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser\ComponentValues;

use Netmosfera\PHPCSSAST\Nodes\ComponentValues\CurlySimpleBlockComponentValue;
use Netmosfera\PHPCSSAST\Nodes\ComponentValues\FunctionComponentValue;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSAST\Parser\ComponentValues\tokensToComponentValues;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\Parser\getToken;
use function Netmosfera\PHPCSSASTTests\Parser\getTokens;

/**
 * Tests in this file:
 *
 * @TODO
 */
class tokensToComponentValuesTest extends TestCase
{
    public function test1(){
        $nodes[] = getToken("foo");
        $nodes[] = getToken(" ");
        $nodes[] = getToken("+123.33");
        $nodes[] = getToken(" ");
        $nodes[] = new FunctionComponentValue(getToken("foo("), [
            getToken("bar"),
            getToken(","),
            getToken(" "),
            new FunctionComponentValue(getToken("foo("), [
                    getToken("bar"),
                    getToken(","),
                    getToken(" "),
                    getToken("baz"),
            ], FALSE),
            getToken("baz"),
        ], FALSE);
        $nodes[] = new CurlySimpleBlockComponentValue([
            getToken("bar"),
            getToken(","),
            getToken(" "),
            new CurlySimpleBlockComponentValue([
                getToken("bar"),
                getToken(","),
                getToken(" "),
                getToken("baz"),
            ], FALSE),
            getToken("baz"),
        ], FALSE);

        $actualNodes = tokensToComponentValues(getTokens(implode("", $nodes)));

        assertMatch($actualNodes, $nodes);
    }
}
