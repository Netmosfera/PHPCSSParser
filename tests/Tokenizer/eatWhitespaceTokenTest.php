<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\WHITESPACES;
use function Netmosfera\PHPCSSAST\Tokenizer\eatWhitespaceToken;
use function Netmosfera\PHPCSSASTDev\Examples\NOT_STARTING_WITH_WHITESPACE;
use Netmosfera\PHPCSSAST\Tokens\WhitespaceToken;
use Netmosfera\PHPCSSAST\Traverser;
use PHPUnit\Framework\TestCase;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class eatWhitespaceTokenTest extends TestCase
{
    function data_whitespaces(){
        return cartesianProduct(
            ANY_UTF8(),
            WHITESPACES(),
            NOT_STARTING_WITH_WHITESPACE()
        );
    }

    /** @dataProvider data_whitespaces */
    function test_whitespaces($prefix, $whitespaces, $rest){
        $t = new Traverser($prefix . $whitespaces . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatWhitespaceToken($t), new WhitespaceToken($whitespaces)));
        self::assertTrue(match($t->eatAll(), $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_no_whitespaces(){
        return cartesianProduct(
            ANY_UTF8(),
            NOT_STARTING_WITH_WHITESPACE()
        );
    }

    /** @dataProvider data_no_whitespaces */
    function test_no_whitespaces($prefix, $rest){
        $t = new Traverser($prefix . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatWhitespaceToken($t), NULL));
        self::assertTrue(match($t->eatAll(), $rest));
    }
}
