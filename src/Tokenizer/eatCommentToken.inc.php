<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Netmosfera\PHPCSSAST\Tokens\Misc\CommentToken;
use function mb_substr;

function eatCommentToken(Traverser $traverser): ?CommentToken{
    $inCommentTraverser = $traverser->createBranch();

    if($inCommentTraverser->eatString("/*") === NULL){
        return NULL;
    }

    $text = $inCommentTraverser->eatPattern('.*?[*][\/]|.*');

    $EOFTerminated = TRUE;
    if(mb_substr($text, -2) === "*/"){
        $EOFTerminated = FALSE;
        $text = mb_substr($text, 0, -2);
    }

    $comment = new CommentToken($text, $EOFTerminated);

    $traverser->importBranch($inCommentTraverser);

    return $comment;
}
