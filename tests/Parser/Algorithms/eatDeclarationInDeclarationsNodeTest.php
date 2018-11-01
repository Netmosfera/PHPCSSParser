<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser\Algorithms;

use Netmosfera\PHPCSSAST\Nodes\Components\DeclarationNode;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSAST\Parser\Algorithms\eatDeclarationInDeclarationsNode;
use function Netmosfera\PHPCSSAST\Parser\ComponentValues\tokensToComponentValues;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\Parser\getTestNodeStream;
use function Netmosfera\PHPCSSASTTests\Parser\getToken;
use function Netmosfera\PHPCSSASTTests\Parser\getTokens;
use function Netmosfera\PHPCSSASTTests\Parser\stringifyNodeStreamRest;

// @TODO make tests dynamic with data providers

/**
 * Tests in this file:
 *
 * #1 | returns NULL if EOF
 * #2 | returns NULL if doesn't start with identifier
 * #3 | returns NULL if EOF after whitespace after identifier
 * #4 | returns NULL if not colon after whitespace after identifier
 * #5 | returns object, terminating with EOF
 * #6 | returns object, terminating with semicolon
 */
class eatDeclarationInDeclarationsNodeTest extends TestCase
{
    function test1(){
        $declaration = NULL;

        $stream = getTestNodeStream(TRUE, "");
        $actualDeclaration = eatDeclarationInDeclarationsNode($stream);

        assertMatch($actualDeclaration, $declaration);
        assertMatch(stringifyNodeStreamRest($stream), "");
    }

    function test2(){
        $declaration = NULL;

        $stream = getTestNodeStream(TRUE, "+123 : red");
        $actualDeclaration = eatDeclarationInDeclarationsNode($stream);

        assertMatch($actualDeclaration, $declaration);
        assertMatch(stringifyNodeStreamRest($stream), "+123 : red");
    }

    function test3(){
        $declaration = NULL;

        $stream = getTestNodeStream(TRUE, "background    /* lol */      /* baz */      ");
        $actualDeclaration = eatDeclarationInDeclarationsNode($stream);

        assertMatch($actualDeclaration, $declaration);
        assertMatch(stringifyNodeStreamRest($stream), "background    /* lol */      /* baz */      ");
    }

    function test4(){
        $declaration = NULL;

        $stream = getTestNodeStream(TRUE, "background    /* lol */      /* baz */      qux");
        $actualDeclaration = eatDeclarationInDeclarationsNode($stream);

        assertMatch($actualDeclaration, $declaration);
        assertMatch(stringifyNodeStreamRest($stream), "background    /* lol */      /* baz */      qux");
    }

    function test5(){
        $css = "background /* lol */ : red /* lool */ foo /* after */ ";
        $declaration = new DeclarationNode(
            getToken("background"),
            tokensToComponentValues(getTokens(" /* lol */ ")),
            tokensToComponentValues(getTokens(" ")),
            tokensToComponentValues(getTokens("red /* lool */ foo"))
        );

        $stream = getTestNodeStream(TRUE, $css);
        $actualDeclaration = eatDeclarationInDeclarationsNode($stream);

        assertMatch($actualDeclaration, $declaration);
        assertMatch(stringifyNodeStreamRest($stream), " /* after */ ");
    }

    function test6(){
        $css = "background /* lol */ : red /* lool */ foo /* after */ ; foo bar";
        $declaration = new DeclarationNode(
            getToken("background"),
            tokensToComponentValues(getTokens(" /* lol */ ")),
            tokensToComponentValues(getTokens(" ")),
            tokensToComponentValues(getTokens("red /* lool */ foo"))
        );

        $stream = getTestNodeStream(TRUE, $css);
        $actualDeclaration = eatDeclarationInDeclarationsNode($stream);

        assertMatch($actualDeclaration, $declaration);
        assertMatch(stringifyNodeStreamRest($stream), " /* after */ ; foo bar");
    }
}
