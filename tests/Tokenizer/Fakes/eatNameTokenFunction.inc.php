<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokenizer\Fakes;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use Netmosfera\PHPCSSAST\Tokenizer\Traverser;

function eatNameTokenFunction(?NameToken $name): Closure{
    return function(Traverser $traverser) use($name): ?NameToken{
        if($name === NULL){
            return NULL;
        }else{
            $stringValue = (String)$name;
            return $traverser->eatString($stringValue) === NULL ? NULL : $name;
        }
    };
}
