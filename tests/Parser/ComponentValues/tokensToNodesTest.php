<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser\ComponentValues;

use Netmosfera\PHPCSSAST\Nodes\ComponentValues\ComponentValuesSeq;
use Netmosfera\PHPCSSAST\Nodes\ComponentValues\FunctionComponentValue;
use Netmosfera\PHPCSSAST\Nodes\ComponentValues\SimpleBlockComponentValue;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSAST\Parser\ComponentValues\tokensToNodes;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\Parser\getToken;
use function Netmosfera\PHPCSSASTTests\Parser\getTokens;

/**
 * Tests in this file:
 *
 * @TODO
 */
class tokensToNodesTest extends TestCase
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
        $nodes[] = new SimpleBlockComponentValue("{", [
            getToken("bar"),
            getToken(","),
            getToken(" "),
            new SimpleBlockComponentValue("{", [
                getToken("bar"),
                getToken(","),
                getToken(" "),
                getToken("baz"),
            ], FALSE),
            getToken("baz"),
        ], FALSE);

        $nodes = new ComponentValuesSeq($nodes); // @TODO checked nodes

        $actualNodes = tokensToNodes(
            getTokens((String)$nodes)
        );

        assertMatch($actualNodes, $nodes);
    }
}
