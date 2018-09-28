<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Operators;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Operators\ColonToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\CommaToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\SemicolonToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\LeftParenthesisToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\LeftCurlyBracketToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\RightParenthesisToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\LeftSquareBracketToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\RightCurlyBracketToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\RightSquareBracketToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class OperatorsTest extends TestCase
{
    function data1(){
        yield [ColonToken::CLASS, ":"];
        yield [CommaToken::CLASS, ","];
        yield [LeftCurlyBracketToken::CLASS, "{"];
        yield [LeftParenthesisToken::CLASS, "("];
        yield [LeftSquareBracketToken::CLASS, "["];
        yield [RightCurlyBracketToken::CLASS, "}"];
        yield [RightParenthesisToken::CLASS, ")"];
        yield [RightSquareBracketToken::CLASS, "]"];
        yield [SemicolonToken::CLASS, ";"];
    }

    /** @dataProvider data1 */
    function test1(String $className, String $value){
        $delimiter1 = new $className();
        $delimiter2 = new $className();

        assertMatch($delimiter1, $delimiter2);

        assertMatch($value, (String)$delimiter1);
        assertMatch((String)$delimiter1, (String)$delimiter2);
    }
}
