<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Misc;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use function Netmosfera\PHPCSSASTTests\assertThrowsType;
use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedCommentToken;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | test getters
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
        $object1 = new CheckedCommentToken($comment, $terminatedWithEOF);
        $object2 = new CheckedCommentToken($comment, $terminatedWithEOF);

        assertMatch($object1, $object2);

        $commentEnd = $terminatedWithEOF ? "" : "*/";
        assertMatch("/*" . $comment . $commentEnd, (String)$object1);
        assertMatch((String)$object1, (String)$object2);

        assertMatch($comment, $object1->getText());
        assertMatch($object1->getText(), $object2->getText());

        assertMatch($terminatedWithEOF, $object1->isTerminatedWithEOF());
        assertMatch($object1->isTerminatedWithEOF(), $object2->isTerminatedWithEOF());
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data2(){
        $comments[] = "*/";
        $comments[] = "comment */ text";
        return cartesianProduct($comments);
    }

    /** @dataProvider data2 */
    function test2(String $comment){
        assertThrowsType(InvalidToken::CLASS, function() use($comment){
            new CheckedCommentToken($comment, FALSE);
        });
    }
}
