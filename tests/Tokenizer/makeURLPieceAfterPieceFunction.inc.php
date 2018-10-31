<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

use Netmosfera\PHPCSSAST\Tokens\Escapes\CodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EncodedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\URLBitToken;

function makeURLPieceAfterPieceFunction(){
    return function($afterPiece, Bool $isLast){
        $URLBit = "foo-bar";
        $data = [];
        if($afterPiece === NULL){
            $data[] = new URLBitToken($URLBit);
            $data[] = new EncodedCodePointEscapeToken("@");
            $data[] = new EncodedCodePointEscapeToken("\0");
            $data[] = new CodePointEscapeToken("Fac", NULL);
            $data[] = new CodePointEscapeToken("0", NULL);
        }elseif($afterPiece instanceof URLBitToken){
            $data[] = new EncodedCodePointEscapeToken("@");
            $data[] = new EncodedCodePointEscapeToken("\0");
            $data[] = new CodePointEscapeToken("Fac", NULL);
            $data[] = new CodePointEscapeToken("0", NULL);
        }elseif($afterPiece instanceof ValidEscapeToken){
            $data[] = new URLBitToken($URLBit);
            $data[] = new EncodedCodePointEscapeToken("@");
            $data[] = new EncodedCodePointEscapeToken("\0");
            $data[] = new CodePointEscapeToken("Fac", NULL);
            $data[] = new CodePointEscapeToken("0", NULL);
        }else{
            assert(FALSE);
        }
        return $data;
    };
}
