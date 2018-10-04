<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\TokensChecked\Misc;

use Netmosfera\PHPCSSAST\Tokens\Misc\CommentToken;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use function strpos;

class CheckedCommentToken extends CommentToken
{
    public function __construct(String $text, Bool $EOFTerminated){
        if(strpos($text, "*/") !== FALSE){
            throw new InvalidToken();
        }
        parent::__construct($text, $EOFTerminated);
    }
}
