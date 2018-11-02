<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser\Components;

use Netmosfera\PHPCSSAST\Nodes\Components\CurlySimpleBlockComponent;
use Netmosfera\PHPCSSAST\Nodes\Components\FunctionComponent;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSAST\Parser\Components\tokensToComponents;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\Parser\getTestToken;
use function Netmosfera\PHPCSSASTTests\Parser\getTestTokens;

/**
 * Tests in this file:
 *
 * @TODO
 */
class tokensToComponentsTest extends TestCase
{
    public function test1(){
        $nodes[] = getTestToken("foo");
        $nodes[] = getTestToken(" ");
        $nodes[] = getTestToken("+123.33");
        $nodes[] = getTestToken(" ");
        $nodes[] = new FunctionComponent(getTestToken("foo("), [
            getTestToken("bar"),
            getTestToken(","),
            getTestToken(" "),
            new FunctionComponent(getTestToken("foo("), [
                    getTestToken("bar"),
                    getTestToken(","),
                    getTestToken(" "),
                    getTestToken("baz"),
            ], FALSE),
            getTestToken("baz"),
        ], FALSE);
        $nodes[] = new CurlySimpleBlockComponent([
            getTestToken("bar"),
            getTestToken(","),
            getTestToken(" "),
            new CurlySimpleBlockComponent([
                getTestToken("bar"),
                getTestToken(","),
                getTestToken(" "),
                getTestToken("baz"),
            ], FALSE),
            getTestToken("baz"),
        ], FALSE);

        $actualNodes = tokensToComponents(getTestTokens(implode("", $nodes)));

        assertMatch($actualNodes, $nodes);
    }
}
