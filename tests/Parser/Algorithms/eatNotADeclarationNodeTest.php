<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser\Algorithms;

use Netmosfera\PHPCSSAST\Nodes\Components\InvalidDeclarationNode;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSAST\Parser\Algorithms\eatNotADeclarationNode;
use function Netmosfera\PHPCSSAST\Parser\ComponentValues\tokensToComponentValues;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\Parser\getTestNodeStream;
use function Netmosfera\PHPCSSASTTests\Parser\getTokens;
use function Netmosfera\PHPCSSASTTests\Parser\stringifyNodeStreamRest;

// @TODO make tests dynamic with data providers

/**
 * Tests in this file:
 *
 * #1 | test loop terminated with ;
 * #2 | test loop terminated with EOF
 */
class eatNotADeclarationNodeTest extends TestCase
{
    function test1(){
        $pieces = tokensToComponentValues(getTokens("foo bar baz"));
        $invalidDeclaration = new InvalidDeclarationNode($pieces);
        $rest = "  /* dsf */    ; foo";

        $stream = getTestNodeStream(TRUE, $invalidDeclaration . $rest);
        $actualInvalidDeclaration = eatNotADeclarationNode($stream);

        assertMatch($actualInvalidDeclaration, $invalidDeclaration);
        assertMatch(stringifyNodeStreamRest($stream), $rest);
    }

    function test2(){
        $pieces = tokensToComponentValues(getTokens("foo bar baz"));
        $invalidDeclaration = new InvalidDeclarationNode($pieces);
        $rest = "  /* dsf */    ";

        $stream = getTestNodeStream(TRUE, $invalidDeclaration . $rest);
        $actualInvalidDeclaration = eatNotADeclarationNode($stream);

        assertMatch($actualInvalidDeclaration, $invalidDeclaration);
        assertMatch(stringifyNodeStreamRest($stream), $rest);
    }
}
