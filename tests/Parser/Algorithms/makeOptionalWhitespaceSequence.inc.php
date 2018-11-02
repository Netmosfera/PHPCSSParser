<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use function Netmosfera\PHPCSSASTTests\Parser\getTestComponent;

function makeOptionalWhitespaceSequence(){
    return function($afterPiece, Bool $isLast){
        $data = [];
        if($afterPiece instanceof WhitespaceToken){
            $data[] = getTestComponent("/* comment */");
        }else{
            $data[] = getTestComponent("    ");
            $data[] = getTestComponent("/* comment */");
        }
        return $data;
    };
}
