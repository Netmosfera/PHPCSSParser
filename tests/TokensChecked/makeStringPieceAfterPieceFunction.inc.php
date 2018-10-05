<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked;

use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Strings\CheckedStringBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEOFEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedContinuationEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;

function makeStringPieceAfterPieceFunction(Bool $EOFTerminated){
    return function($afterPiece, Bool $isLast) use($EOFTerminated){
        $stringBit = "string \u{2764} bit";
        $data = [];
        if($afterPiece === NULL){
            $data[] = new CheckedStringBitToken($stringBit);
            $data[] = new CheckedContinuationEscapeToken("\n");
            $data[] = new CheckedEncodedCodePointEscapeToken("@");
            $data[] = new CheckedEncodedCodePointEscapeToken("\0");
            $data[] = new CheckedCodePointEscapeToken("Fac", NULL);
            $data[] = new CheckedCodePointEscapeToken("0", NULL);
            if($EOFTerminated && $isLast){
                $data[] = new CheckedEOFEscapeToken();
            }
        }elseif($afterPiece instanceof StringBitToken){
            $data[] = new CheckedContinuationEscapeToken("\n");
            $data[] = new CheckedEncodedCodePointEscapeToken("@");
            $data[] = new CheckedEncodedCodePointEscapeToken("\0");
            $data[] = new CheckedCodePointEscapeToken("Fac", NULL);
            $data[] = new CheckedCodePointEscapeToken("0", NULL);
            if($EOFTerminated && $isLast){
                $data[] = new CheckedEOFEscapeToken();
            }
        }elseif($afterPiece instanceof EscapeToken){
            $data[] = new CheckedStringBitToken($stringBit);
            $data[] = new CheckedContinuationEscapeToken("\n");
            $data[] = new CheckedEncodedCodePointEscapeToken("@");
            $data[] = new CheckedEncodedCodePointEscapeToken("\0");
            $data[] = new CheckedCodePointEscapeToken("Fac", NULL);
            $data[] = new CheckedCodePointEscapeToken("0", NULL);
            if($EOFTerminated && $isLast){
                $data[] = new CheckedEOFEscapeToken();
            }
        }
        return $data;
    };
}
