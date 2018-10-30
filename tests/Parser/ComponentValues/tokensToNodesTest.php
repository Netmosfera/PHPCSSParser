<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser\ComponentValues;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Nodes\ComponentValues\Nodes;
use Netmosfera\PHPCSSAST\Nodes\ComponentValues\FunctionNode;
use Netmosfera\PHPCSSAST\Nodes\ComponentValues\SimpleBlockNode;
use function Netmosfera\PHPCSSAST\Parser\ComponentValues\tokensToNodes;
use function Netmosfera\PHPCSSASTTests\Parser\getTokens;
use function Netmosfera\PHPCSSASTTests\Parser\getToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;

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
        $nodes[] = new FunctionNode(getToken("foo("), [
            getToken("bar"),
            getToken(","),
            getToken(" "),
            new FunctionNode(getToken("foo("), [
                    getToken("bar"),
                    getToken(","),
                    getToken(" "),
                    getToken("baz"),
            ], FALSE),
            getToken("baz"),
        ], FALSE);
        $nodes[] = new SimpleBlockNode("{", [
            getToken("bar"),
            getToken(","),
            getToken(" "),
            new SimpleBlockNode("{", [
                getToken("bar"),
                getToken(","),
                getToken(" "),
                getToken("baz"),
            ], FALSE),
            getToken("baz"),
        ], FALSE);

        $nodes = new Nodes($nodes); // @TODO checked nodes

        $actualNodes = tokensToNodes(
            getTokens((String)$nodes)
        );

        assertMatch($actualNodes, $nodes);
    }
}
