<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Misc;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedCommentToken;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\assertThrowsType;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | test getters
 * #2 | test invalid
 */
class CommentTokenTest extends TestCase
{
    function data1(){
        $comments[] = "comment /* text";
        $comments[] = "comment \u{2764} text";
        $comments[] = "";
        return cartesianProduct($comments, [TRUE, FALSE]);
    }

    /** @dataProvider data1 */
    function test1(String $comment, Bool $terminatedWithEOF){
        $comment1 = new CheckedCommentToken($comment, $terminatedWithEOF);
        $comment2 = new CheckedCommentToken($comment, $terminatedWithEOF);

        assertMatch($comment1, $comment2);

        $commentEnd = $terminatedWithEOF ? "" : "*/";
        assertMatch("/*" . $comment . $commentEnd, (String)$comment1);
        assertMatch((String)$comment1, (String)$comment2);

        assertMatch($comment, $comment1->getText());
        assertMatch($comment1->getText(), $comment2->getText());

        assertMatch($terminatedWithEOF, $comment1->isTerminatedWithEOF());
        assertMatch($comment1->isTerminatedWithEOF(), $comment2->isTerminatedWithEOF());
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data2(){
        $comments[] = "*/";
        $comments[] = "comment */ text";
        $comments[] = "comment text */";
        return cartesianProduct($comments);
    }

    /** @dataProvider data2 */
    function test2(String $comment){
        assertThrowsType(InvalidToken::CLASS, function() use($comment){
            new CheckedCommentToken($comment, FALSE);
        });
    }
}
