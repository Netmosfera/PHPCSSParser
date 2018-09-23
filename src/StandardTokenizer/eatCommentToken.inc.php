<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function mb_substr;
use Netmosfera\PHPCSSAST\Tokens\Misc\CommentToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Consumes a {@see CommentToken}, if any.
 */
function eatCommentToken(Traverser $traverser): ?CommentToken{
    $inCommentTraverser = $traverser->createBranch();

    if($inCommentTraverser->eatStr("/*") === NULL){
        return NULL;
    }

    $text = $inCommentTraverser->eatExp('.*?[*][\/]|.*');

    $EOFTerminated = TRUE;
    if(mb_substr($text, -2) === "*/"){
        $EOFTerminated = FALSE;
        $text = mb_substr($text, 0, -2);
    }

    $comment = new CommentToken($text, $EOFTerminated);

    $traverser->importBranch($inCommentTraverser);

    return $comment;
}
