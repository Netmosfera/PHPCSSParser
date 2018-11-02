<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser\Algorithms;

use Netmosfera\PHPCSSAST\Nodes\MainNodes\InvalidDeclarationNode;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSAST\Parser\Algorithms\eatInvalidDeclarationNode;
use function Netmosfera\PHPCSSAST\Parser\Components\tokensToComponents;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\Parser\getTestComponentStream;
use function Netmosfera\PHPCSSASTTests\Parser\getTestTokens;
use function Netmosfera\PHPCSSASTTests\Parser\stringifyComponentStreamRest;

// @TODO make tests dynamic with data providers

/**
 * Tests in this file:
 *
 * #1 | test loop terminated with ;
 * #2 | test loop terminated with EOF
 */
class eatInvalidDeclarationNodeTest extends TestCase
{
    function test1(){
        $pieces = tokensToComponents(getTestTokens("foo bar baz"));
        $invalidDeclaration = new InvalidDeclarationNode($pieces);
        $rest = "  /* dsf */    ; foo";

        $stream = getTestComponentStream(TRUE, $invalidDeclaration . $rest);
        $actualInvalidDeclaration = eatInvalidDeclarationNode($stream);

        assertMatch($actualInvalidDeclaration, $invalidDeclaration);
        assertMatch(stringifyComponentStreamRest($stream), $rest);
    }

    function test2(){
        $pieces = tokensToComponents(getTestTokens("foo bar baz"));
        $invalidDeclaration = new InvalidDeclarationNode($pieces);
        $rest = "  /* dsf */    ";

        $stream = getTestComponentStream(TRUE, $invalidDeclaration . $rest);
        $actualInvalidDeclaration = eatInvalidDeclarationNode($stream);

        assertMatch($actualInvalidDeclaration, $invalidDeclaration);
        assertMatch(stringifyComponentStreamRest($stream), $rest);
    }
}
