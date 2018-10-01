<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedCommentToken;
use function Netmosfera\PHPCSSAST\StandardTokenizer\eatCommentToken;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | not starting with /*
 * #2 | terminated with EOF
 * #3 | terminated with * and /
 */
class eatCommentTokenTest extends TestCase
{
    public function data1(){
        $rest[] = "";
        $rest[] = "not \u{2764} a \u{2764} comment";
        $rest[] = "*/ also this is not a comment";
        $rest[] = " /* this too is not a comment, because it's prefixed by a space";
        return cartesianProduct(ANY_UTF8(), $rest);
    }

    /** @dataProvider data1 */
    public function test1(String $prefix, String $rest){
        $traverser = getTraverser($prefix, $rest);
        $expected = NULL;
        $actual = eatCommentToken($traverser);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data2(){
        return cartesianProduct(ANY_UTF8(), $this->commentTexts());
    }

    /** @dataProvider data2 */
    public function test2(String $prefix, String $text){
        $traverser = getTraverser($prefix, "/*" . $text);
        $expected = new CheckedCommentToken($text, TRUE);
        $actual = eatCommentToken($traverser);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), "");
    }

    public function data3(){
        $rest[] = "";
        $rest[] = "sample \u{2764} string";
        $rest[] = "this is used to test that */ is matched lazily";
        return cartesianProduct(ANY_UTF8(), $this->commentTexts(), $rest);
    }

    /** @dataProvider data3 */
    public function test3(String $prefix, String $text, String $rest){
        $traverser = getTraverser($prefix, "/*" . $text . "*/" . $rest);
        $expected = new CheckedCommentToken($text, FALSE);
        $actual = eatCommentToken($traverser);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function commentTexts(){
        $sequences[] = "";
        $sequences[] = "comment text";
        $sequences[] = "\t \n \r \r\n \f";
        $sequences[] = "sample \u{2764} string";
        $sequences[] = "comment terminating with incomplete comment-end *";
        $sequences[] = "comment text can contain /* without causing a parse error";
        return $sequences;
    }
}
