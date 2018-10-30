<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser\Algorithms;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Nodes\Components\InvalidDeclarationNode;
use function Netmosfera\PHPCSSAST\Parser\ComponentValues\tokensToNodes;
use function Netmosfera\PHPCSSAST\Parser\Algorithms\eatNotADeclaration;
use function Netmosfera\PHPCSSASTTests\Parser\stringifyNodes;
use function Netmosfera\PHPCSSASTTests\Parser\getNodeStream;
use function Netmosfera\PHPCSSASTTests\Parser\getTokens;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | test loop terminated with ;
 * #2 | test loop terminated with EOF
 */
class eatNotADeclarationTest extends TestCase
{
    function test1(){
        $pieces = tokensToNodes(getTokens("foo bar baz"))->nodes();
        $invalidDeclaration = new InvalidDeclarationNode($pieces);
        $rest = "  /* dsf */    ; pooooop";

        $stream = getNodeStream(TRUE, $invalidDeclaration . $rest);
        $actualInvalidDeclaration = eatNotADeclaration($stream);

        assertMatch($actualInvalidDeclaration, $invalidDeclaration);
        assertMatch(stringifyNodes($stream), $rest);
    }

    function test2(){
        $pieces = tokensToNodes(getTokens("foo bar baz"))->nodes();
        $invalidDeclaration = new InvalidDeclarationNode($pieces);
        $rest = "  /* dsf */    ";

        $stream = getNodeStream(TRUE, $invalidDeclaration . $rest);
        $actualInvalidDeclaration = eatNotADeclaration($stream);

        assertMatch($actualInvalidDeclaration, $invalidDeclaration);
        assertMatch(stringifyNodes($stream), $rest);
    }
}
