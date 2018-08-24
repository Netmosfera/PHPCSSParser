<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\CommentToken;
use Netmosfera\PHPCSSAST\Traverser;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function eatCommentToken(Traverser $t){
    $ct = $t->createBranch();
    if(has($ct->eatStr("/*"))){
        $text = $ct->eatExp('.*(?=[*][\/])|.*'); // @TODO this is probably slow
        $terminated = has($ct->eatStr("*/"));
        $comment = new CommentToken($text, $terminated);
        $t->importBranch($ct);
        return $comment;
    }
    return NULL;
}
