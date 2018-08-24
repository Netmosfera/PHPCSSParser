<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\eatComment;
use function Netmosfera\PHPCSSASTDev\Examples\getEitherEmptyOrNonEmptyAnyCodePointSeqsSet;
use function Netmosfera\PHPCSSASTDev\Examples\getAnyCodePointSeqsSet;
use Netmosfera\PHPCSSAST\Tokens\Comment;
use Netmosfera\PHPCSSAST\Traverser;
use PHPUnit\Framework\TestCase;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class isNumberStartTest extends TestCase
{
    function getTexts(){
        return [
            "",
            "   ",
            "trap *",
            " hello ",
            "\u{2764}\u{2764}\u{2764}",
        ];
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_terminated(){
        return cartesianProduct(
            getEitherEmptyOrNonEmptyAnyCodePointSeqsSet(),
            $this->getTexts(),
            getAnyCodePointSeqsSet()
        );
    }

    /** @dataProvider data_terminated */
    function test_terminated(String $prefix, String $text, String $rest){
        $t = new Traverser($prefix . "/*" . $text . "*/" . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatComment($t), new Comment($text)));
        self::assertTrue(match($t->eatAll(), $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_unterminated(){
        return cartesianProduct(
            getEitherEmptyOrNonEmptyAnyCodePointSeqsSet(),
            $this->getTexts()
        );
    }

    /** @dataProvider data_unterminated */
    function test_unterminated(String $prefix, String $text){
        $t = new Traverser($prefix . "/*" . $text, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatComment($t), new Comment($text, FALSE)));
        self::assertTrue(match($t->eatAll(), ""));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_not_a_comment(){
        return cartesianProduct(
            getEitherEmptyOrNonEmptyAnyCodePointSeqsSet(),
            ["not a comment", ""]
        );
    }

    /** @dataProvider data_not_a_comment */
    function test_not_a_comment(String $prefix, String $rest){
        $t = new Traverser($prefix . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatComment($t), NULL));
        self::assertTrue(match($t->eatAll(), $rest));
    }
}
