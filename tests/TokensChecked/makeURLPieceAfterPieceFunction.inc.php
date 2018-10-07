<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked;

use Netmosfera\PHPCSSAST\Tokens\Names\URLs\URLBitToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedURLBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;

function makeURLPieceAfterPieceFunction(){
    return function($afterPiece, Bool $isLast){
        $URLBit = "foo-bar";
        $data = [];
        if($afterPiece === NULL){
            $data[] = new CheckedURLBitToken($URLBit);
            $data[] = new CheckedEncodedCodePointEscapeToken("@");
            $data[] = new CheckedEncodedCodePointEscapeToken("\0");
            $data[] = new CheckedCodePointEscapeToken("Fac", NULL);
            $data[] = new CheckedCodePointEscapeToken("0", NULL);
        }elseif($afterPiece instanceof URLBitToken){
            $data[] = new CheckedEncodedCodePointEscapeToken("@");
            $data[] = new CheckedEncodedCodePointEscapeToken("\0");
            $data[] = new CheckedCodePointEscapeToken("Fac", NULL);
            $data[] = new CheckedCodePointEscapeToken("0", NULL);
        }elseif($afterPiece instanceof ValidEscapeToken){
            $data[] = new CheckedURLBitToken($URLBit);
            $data[] = new CheckedEncodedCodePointEscapeToken("@");
            $data[] = new CheckedEncodedCodePointEscapeToken("\0");
            $data[] = new CheckedCodePointEscapeToken("Fac", NULL);
            $data[] = new CheckedCodePointEscapeToken("0", NULL);
        }else{
            assert(FALSE);
        }
        return $data;
    };
}
