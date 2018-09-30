<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use function mb_substr;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedCommentToken;

function eatCommentToken(Traverser $traverser): ?CheckedCommentToken{
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

    $comment = new CheckedCommentToken($text, $EOFTerminated);

    $traverser->importBranch($inCommentTraverser);

    return $comment;
}
