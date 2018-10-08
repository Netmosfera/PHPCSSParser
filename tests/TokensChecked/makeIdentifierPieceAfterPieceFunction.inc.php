<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked;

use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;

function makeIdentifierPieceAfterPieceFunction(){
    return function($afterPiece, Bool $isLast){
        $data = [];
        if($afterPiece === NULL){
            $data[] = new CheckedNameBitToken("S");
            $data[] = new CheckedNameBitToken("SN");
            $data[] = new CheckedNameBitToken("SNN");
            $data[] = new CheckedNameBitToken("-S");
            $data[] = new CheckedNameBitToken("-SN");
            $data[] = new CheckedNameBitToken("-SNN");
            $data[] = new CheckedNameBitToken("--");
            $data[] = new CheckedNameBitToken("--N");
            $data[] = new CheckedNameBitToken("--NN");
            $data[] = new CheckedNameBitToken("--NNN");
            $data[] = new CheckedEncodedCodePointEscapeToken("@");
            $data[] = new CheckedCodePointEscapeToken("2764", NULL);
            if($isLast === FALSE){
                // This is used to test "-" followed by escape (alone it is invalid)
                $data[] = new CheckedNameBitToken("-");
            }
        }elseif($afterPiece instanceof CheckedNameBitToken){
            $data[] = new CheckedEncodedCodePointEscapeToken("@");
            $data[] = new CheckedCodePointEscapeToken("2764", NULL);
        }elseif($afterPiece instanceof EscapeToken){
            $data[] = new CheckedEncodedCodePointEscapeToken("@");
            $data[] = new CheckedCodePointEscapeToken("2764", NULL);
            $data[] = new CheckedNameBitToken("N");
            $data[] = new CheckedNameBitToken("NN");
            $data[] = new CheckedNameBitToken("NNN");
        }
        return $data;
    };
}
