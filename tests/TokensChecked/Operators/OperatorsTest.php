<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Operators;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\Operators\CheckedColonToken;
use Netmosfera\PHPCSSAST\TokensChecked\Operators\CheckedCommaToken;
use Netmosfera\PHPCSSAST\TokensChecked\Operators\CheckedSemicolonToken;
use Netmosfera\PHPCSSAST\TokensChecked\Operators\CheckedLeftParenthesisToken;
use Netmosfera\PHPCSSAST\TokensChecked\Operators\CheckedLeftCurlyBracketToken;
use Netmosfera\PHPCSSAST\TokensChecked\Operators\CheckedRightParenthesisToken;
use Netmosfera\PHPCSSAST\TokensChecked\Operators\CheckedLeftSquareBracketToken;
use Netmosfera\PHPCSSAST\TokensChecked\Operators\CheckedRightCurlyBracketToken;
use Netmosfera\PHPCSSAST\TokensChecked\Operators\CheckedRightSquareBracketToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class OperatorsTest extends TestCase
{
    public function data1(){
        yield [CheckedColonToken::CLASS, ":"];
        yield [CheckedCommaToken::CLASS, ","];
        yield [CheckedLeftCurlyBracketToken::CLASS, "{"];
        yield [CheckedLeftParenthesisToken::CLASS, "("];
        yield [CheckedLeftSquareBracketToken::CLASS, "["];
        yield [CheckedRightCurlyBracketToken::CLASS, "}"];
        yield [CheckedRightParenthesisToken::CLASS, ")"];
        yield [CheckedRightSquareBracketToken::CLASS, "]"];
        yield [CheckedSemicolonToken::CLASS, ";"];
    }

    /** @dataProvider data1 */
    public function test1(String $className, String $value){
        $delimiter1 = new $className();
        $delimiter2 = new $className();
        assertMatch($delimiter1, $delimiter2);
        assertMatch((String)$delimiter1, $value);
    }
}
