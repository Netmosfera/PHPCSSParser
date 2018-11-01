<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokens\Misc;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Misc\CommentToken;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | test getters
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
    public function test1(String $comment, Bool $EOFTerminated){
        $comment1 = new CommentToken($comment, $EOFTerminated);
        $comment2 = new CommentToken($comment, $EOFTerminated);
        assertMatch($comment1, $comment2);
        $commentEnd = $EOFTerminated ? "" : "*/";
        assertMatch((String)$comment1, "/*" . $comment . $commentEnd);
        assertMatch($comment1->text(), $comment);
        assertMatch($comment1->EOFTerminated(), $EOFTerminated);
    }
}
