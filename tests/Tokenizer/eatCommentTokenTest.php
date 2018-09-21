<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Misc\CommentToken;
use function Netmosfera\PHPCSSAST\Tokenizer\eatCommentToken;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTDev\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | not starting with /*
 * #2 | terminated with EOF
 * #3 | terminated with * and /
 */
class eatCommentTokenTest extends TestCase
{
    function data1(){
        $rest[] = "";
        $rest[] = "not \u{2764} a \u{2764} comment";
        $rest[] = "*/ also this is not a comment";
        $rest[] = " /* this too is not a comment, because it's prefixed by a space";
        return cartesianProduct(ANY_UTF8(), $rest);
    }

    /** @dataProvider data1 */
    function test1(String $prefix, String $rest){
        $traverser = getTraverser($prefix, $rest);
        $expected = NULL;
        $actual = eatCommentToken($traverser);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data2(){
        return cartesianProduct(ANY_UTF8(), $this->commentTexts());
    }

    /** @dataProvider data2 */
    function test2(String $prefix, String $text){
        $traverser = getTraverser($prefix, "/*" . $text);
        $expected = new CommentToken($text, TRUE);
        $actual = eatCommentToken($traverser);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), "");
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data3(){
        $rest[] = "";
        $rest[] = "sample \u{2764} string";
        $rest[] = "this is used to test that */ is matched lazily (ie, that stops at the first encountered)";
        return cartesianProduct(ANY_UTF8(), $this->commentTexts(), $rest);
    }

    /** @dataProvider data3 */
    function test3(String $prefix, String $text, String $rest){
        $traverser = getTraverser($prefix, "/*" . $text . "*/" . $rest);
        $expected = new CommentToken($text, FALSE);
        $actual = eatCommentToken($traverser);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function commentTexts(){
        $sequences[] = "";
        $sequences[] = "comment text";
        $sequences[] = "\t \n \r \r\n \f";
        $sequences[] = "sample \u{2764} string";
        $sequences[] = "comment terminating with incomplete comment-end *";
        $sequences[] = "comment text can contain /* without causing a parse error";
        return $sequences;
    }
}
