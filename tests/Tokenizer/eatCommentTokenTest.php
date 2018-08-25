<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSAST\Tokenizer\eatCommentToken;
use function Netmosfera\PHPCSSASTDev\Examples\COMMENT_TEXTS;
use function Netmosfera\PHPCSSASTDev\Examples\NOT_STARTING_WITH_COMMENT_START;
use Netmosfera\PHPCSSAST\Tokens\CommentToken;
use Netmosfera\PHPCSSAST\Traverser;
use PHPUnit\Framework\TestCase;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class eatCommentTokenTest extends TestCase
{
    function data_terminated(){
        return cartesianProduct(
            ANY_UTF8(),
            COMMENT_TEXTS(),
            ANY_UTF8()
        );
    }

    /** @dataProvider data_terminated */
    function test_terminated(String $prefix, String $text, String $rest){
        $t = new Traverser($prefix . "/*" . $text . "*/" . $rest, TRUE);
        $t->eatStr($prefix);
        assertMatch(eatCommentToken($t), new CommentToken($text));
        assertMatch($t->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_unterminated(){
        return cartesianProduct(
            ANY_UTF8(),
            COMMENT_TEXTS()
        );
    }

    /** @dataProvider data_unterminated */
    function test_unterminated(String $prefix, String $text){
        $t = new Traverser($prefix . "/*" . $text, TRUE);
        $t->eatStr($prefix);
        assertMatch(eatCommentToken($t), new CommentToken($text, FALSE));
        assertMatch($t->eatAll(), "");
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_not_a_comment(){
        return cartesianProduct(
            ANY_UTF8(),
            NOT_STARTING_WITH_COMMENT_START()
        );
    }

    /** @dataProvider data_not_a_comment */
    function test_not_a_comment(String $prefix, String $rest){
        $t = new Traverser($prefix . $rest, TRUE);
        $t->eatStr($prefix);
        assertMatch(eatCommentToken($t), NULL);
        assertMatch($t->eatAll(), $rest);
    }
}
