<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedCommentToken;
use function Netmosfera\PHPCSSAST\Tokenizer\eatCommentToken;
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
    private function commentTexts(){
        $texts[] = "";
        $texts[] = "comment text";
        $texts[] = "\t \n \r \r\n \f";
        $texts[] = "sample \u{2764} string";
        $texts[] = "comment terminating with incomplete comment-end *";
        $texts[] = "comment text can contain /* without causing a parse error";
        return $texts;
    }

    //------------------------------------------------------------------------------------

    public function data1(){
        $rest = ANY_UTF8("not a comment start");
        $rest[] = "*/ also not a comment start";
        $rest[] = " /* this too is not a comment start, because it's prefixed by a space";
        return cartesianProduct(ANY_UTF8(), $rest);
    }

    /** @dataProvider data1 */
    public function test1(String $prefix, String $rest){
        $comment = NULL;

        $traverser = getTraverser($prefix, $rest);
        $actualComment = eatCommentToken($traverser);

        assertMatch($actualComment, $comment);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data2(){
        return cartesianProduct(ANY_UTF8(), $this->commentTexts());
    }

    /** @dataProvider data2 */
    public function test2(String $prefix, String $text){
        $comment = new CheckedCommentToken($text, TRUE);

        $traverser = getTraverser($prefix, $comment . "");
        $actualComment = eatCommentToken($traverser);

        assertMatch($actualComment, $comment);
        assertMatch($traverser->eatAll(), "");
    }

    public function data3(){
        $rest = ANY_UTF8();
        $rest[] = "this is used to test that */ is matched lazily";
        return cartesianProduct(ANY_UTF8(), $this->commentTexts(), $rest);
    }

    /** @dataProvider data3 */
    public function test3(String $prefix, String $text, String $rest){
        $comment = new CheckedCommentToken($text, FALSE);

        $traverser = getTraverser($prefix, $comment . $rest);
        $actualComment = eatCommentToken($traverser);

        assertMatch($actualComment, $comment);
        assertMatch($traverser->eatAll(), $rest);
    }
}
