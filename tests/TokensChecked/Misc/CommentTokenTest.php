<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Misc;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedCommentToken;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\assertThrowsType;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | test getters
 * #2 | test invalid
 */
class CommentTokenTest extends TestCase
{
    public function data1(){
        $comments[] = "comment /* text";
        $comments[] = "comment \u{2764} text";
        $comments[] = "";
        return cartesianProduct($comments, [TRUE, FALSE]);
    }

    /** @dataProvider data1 */
    public function test1(String $comment, Bool $precedesEOF){
        $comment1 = new CheckedCommentToken($comment, $precedesEOF);
        $comment2 = new CheckedCommentToken($comment, $precedesEOF);
        assertMatch($comment1, $comment2);
        $commentEnd = $precedesEOF ? "" : "*/";
        assertMatch((String)$comment1, "/*" . $comment . $commentEnd);
        assertMatch($comment1->text(), $comment);
        assertMatch($comment1->precedesEOF(), $precedesEOF);
    }

    public function data2(){
        $comments[] = "*/";
        $comments[] = "comment */ text";
        $comments[] = "comment text */";
        return cartesianProduct($comments);
    }

    /** @dataProvider data2 */
    public function test2(String $comment){
        assertThrowsType(InvalidToken::CLASS, function() use($comment){
            new CheckedCommentToken($comment, FALSE);
        });
    }
}
