<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\eatWhitespaces;
use Netmosfera\PHPCSSAST\Tokens\Whitespaces;
use Netmosfera\PHPCSSAST\Traverser;
use PHPUnit\Framework\TestCase;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class eatWhitespacesTest extends TestCase
{
    function getRestThatIsNotPrefixedWithWhitespace(){
        return [
            "skip \u{2764} me",
            "",
        ];
    }

    function getWhitespaces(){
        return [
            " ", "\t", "\r", "\n", "\r\n", "\f", "\t\t\t", "     ",
            "     \t       \r        \n      \f         \r\n     ",
        ];
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_whitespaces(){
        return cartesianProduct(getPrefixes(), $this->getWhitespaces(), $this->getRestThatIsNotPrefixedWithWhitespace());
    }

    /** @dataProvider data_whitespaces */
    function test_whitespaces(String $prefix, String $whitespaces, String $rest){
        $t = new Traverser($prefix . $whitespaces . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatWhitespaces($t), new Whitespaces($whitespaces)));
        self::assertTrue(match($t->eatAll(), $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_no_whitespaces(){
        return cartesianProduct(getPrefixes(), $this->getRestThatIsNotPrefixedWithWhitespace());
    }

    /** @dataProvider data_no_whitespaces */
    function test_no_whitespaces(String $prefix, String $rest){
        $t = new Traverser($prefix . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatWhitespaces($t), NULL));
        self::assertTrue(match($t->eatAll(), $rest));
    }
}
