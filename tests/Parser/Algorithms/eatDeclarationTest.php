<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser\Algorithms;

use Netmosfera\PHPCSSAST\Nodes\MainNodes\DeclarationNode;
use function Netmosfera\PHPCSSASTTests\Parser\getTestComponents;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSAST\Parser\Algorithms\eatDeclarationNode;
use function Netmosfera\PHPCSSAST\Parser\Components\tokensToComponents;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_CSS;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\makePiecesSample;
use function Netmosfera\PHPCSSASTTests\Parser\getTestComponentStream;
use function Netmosfera\PHPCSSASTTests\Parser\getTestToken;
use function Netmosfera\PHPCSSASTTests\Parser\getTestTokens;
use function Netmosfera\PHPCSSASTTests\Parser\stringifyComponentStreamRest;
use function Netmosfera\PHPCSSASTTests\Tokenizer\makeOptionalWhitespaceSequence;

// @TODO make tests dynamic with data providers

/**
 * Tests in this file:
 *
 * #2 | returns NULL if doesn't start with identifier
 * #3 | returns NULL if EOF after optional whitespace after identifier
 * #4 | returns NULL if not colon after optional whitespace after identifier
 * #5 | returns object, terminating with EOF
 * #6 | returns object, terminating with semicolon
 */
class eatDeclarationTest extends TestCase
{
    function data2(){
        return cartesianProduct([FALSE, TRUE], ANY_CSS("+123 not starting with ident"));
    }

    /** @dataProvider data2 */
    function test2(Bool $testPrefix, String $rest){
        $declaration = NULL;

        $stream = getTestComponentStream($testPrefix, $rest);
        $actualDeclaration = eatDeclarationNode($stream);

        assertMatch($actualDeclaration, $declaration);
        assertMatch(stringifyComponentStreamRest($stream), $rest);
    }

    function data3(){
        return cartesianProduct(
            [FALSE, TRUE],
            makePiecesSample(makeOptionalWhitespaceSequence())
        );
    }

    /** @dataProvider data3 */
    function test3(Bool $testPrefix, array $restPieces){
        $rest = "background" . implode("", $restPieces);
        $declaration = NULL;

        $stream = getTestComponentStream($testPrefix, $rest);
        $actualDeclaration = eatDeclarationNode($stream);

        assertMatch($actualDeclaration, $declaration);
        assertMatch(stringifyComponentStreamRest($stream), $rest);
    }

    function data4(){
        return cartesianProduct(
            [FALSE, TRUE],
            makePiecesSample(makeOptionalWhitespaceSequence())
        );
    }

    /** @dataProvider data4 */
    function test4(Bool $testPrefix, array $restPieces){
        $rest = "background" . implode("", $restPieces) . "foo";
        $declaration = NULL;

        $stream = getTestComponentStream($testPrefix, $rest);
        $actualDeclaration = eatDeclarationNode($stream);

        assertMatch($actualDeclaration, $declaration);
        assertMatch(stringifyComponentStreamRest($stream), $rest);
    }

    function data5(){
        return cartesianProduct(
            [FALSE, TRUE]
        );
    }

    /** @dataProvider data5 */
    function test5(Bool $testPrefix){
        $css = "background /* lol */ : red /* lool */ foo /* after */ ";
        $declaration = new DeclarationNode(
            getTestToken("background"),
            getTestComponents(" /* lol */ "),
            getTestComponents(" "),
            getTestComponents("red /* lool */ foo")
        );

        $stream = getTestComponentStream($testPrefix, $css);
        $actualDeclaration = eatDeclarationNode($stream);

        assertMatch($actualDeclaration, $declaration);
        assertMatch(stringifyComponentStreamRest($stream), " /* after */ ");
    }

    function test6(){
        $css = "background /* lol */ : red /* lool */ foo /* after */ ; foo bar";
        $declaration = new DeclarationNode(
            getTestToken("background"),
            tokensToComponents(getTestTokens(" /* lol */ ")),
            tokensToComponents(getTestTokens(" ")),
            tokensToComponents(getTestTokens("red /* lool */ foo"))
        );

        $stream = getTestComponentStream(TRUE, $css);
        $actualDeclaration = eatDeclarationNode($stream);

        assertMatch($actualDeclaration, $declaration);
        assertMatch(stringifyComponentStreamRest($stream), " /* after */ ; foo bar");
    }
}
