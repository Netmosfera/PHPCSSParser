<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

use Netmosfera\PHPCSSAST\Tokens\Escapes\CodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EncodedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;

function makeIdentifierPieceAfterPieceFunction(){
    return function($afterPiece, Bool $isLast){
        $data = [];
        if($afterPiece === NULL){
            $data[] = new NameBitToken("S");
            $data[] = new NameBitToken("SN");
            $data[] = new NameBitToken("SNN");
            $data[] = new NameBitToken("-S");
            $data[] = new NameBitToken("-SN");
            $data[] = new NameBitToken("-SNN");
            $data[] = new NameBitToken("--");
            $data[] = new NameBitToken("--N");
            $data[] = new NameBitToken("--NN");
            $data[] = new NameBitToken("--NNN");
            $data[] = new EncodedCodePointEscapeToken("@");
            $data[] = new CodePointEscapeToken("2764", NULL);
            if($isLast === FALSE){
                // This is used to test "-" followed by escape (alone it is invalid)
                $data[] = new NameBitToken("-");
            }
        }elseif($afterPiece instanceof NameBitToken){
            $data[] = new EncodedCodePointEscapeToken("@");
            $data[] = new CodePointEscapeToken("2764", NULL);
        }elseif($afterPiece instanceof EscapeToken){
            $data[] = new EncodedCodePointEscapeToken("@");
            $data[] = new CodePointEscapeToken("2764", NULL);
            $data[] = new NameBitToken("N");
            $data[] = new NameBitToken("NN");
            $data[] = new NameBitToken("NNN");
        }
        return $data;
    };
}
