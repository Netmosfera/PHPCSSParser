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
        $object1 = new $className();
        $object2 = new $className();

        assertMatch($object1, $object2);

        assertMatch($value, (String)$object1);
        assertMatch((String)$object1, (String)$object2);
    }
}
