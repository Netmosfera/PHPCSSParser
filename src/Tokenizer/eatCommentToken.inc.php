<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use function mb_substr;
use Netmosfera\PHPCSSAST\Tokens\Misc\CommentToken;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedCommentToken;

function eatCommentToken(
    Traverser $traverser,
    String $CommentTokenClass = CheckedCommentToken::CLASS
): ?CommentToken{
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

    $comment = new CheckedCommentToken($text, $EOFTerminated);

    $traverser->importBranch($inCommentTraverser);

    return $comment;
}
