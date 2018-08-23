<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer\Tools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\Tokenizer\has;
use Netmosfera\PHPCSSAST\Tokens\Comment;
use Netmosfera\PHPCSSAST\Traverser;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function eatComment(Traverser $t){
    $ct = $t->createBranch();
    if(has($ct->eatStr("/*"))){
        $text = $ct->eatExp('.*(?=[*][\/])|.*'); // @TODO this is probably slow
        $terminated = has($ct->eatStr("*/"));
        $comment = new Comment($text, $terminated);
        $t->importBranch($ct);
        return $comment;
    }
    return NULL;
}
