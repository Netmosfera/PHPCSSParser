<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

use Netmosfera\PHPCSSAST\Tokens\Escapes\CodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ContinuationEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EncodedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EOFEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringBitToken;

function makeStringPieceAfterPieceFunction(Bool $EOFTerminated){
    return function($afterPiece, Bool $isLast) use($EOFTerminated){
        $stringBit = "string \u{2764} bit";
        $data = [];
        if($afterPiece === NULL){
            $data[] = new StringBitToken($stringBit);
            $data[] = new ContinuationEscapeToken("\n");
            $data[] = new EncodedCodePointEscapeToken("@");
            $data[] = new EncodedCodePointEscapeToken("\0");
            $data[] = new CodePointEscapeToken("Fac", NULL);
            $data[] = new CodePointEscapeToken("0", NULL);
            if($EOFTerminated && $isLast){
                $data[] = new EOFEscapeToken();
            }
        }elseif($afterPiece instanceof StringBitToken){
            $data[] = new ContinuationEscapeToken("\n");
            $data[] = new EncodedCodePointEscapeToken("@");
            $data[] = new EncodedCodePointEscapeToken("\0");
            $data[] = new CodePointEscapeToken("Fac", NULL);
            $data[] = new CodePointEscapeToken("0", NULL);
            if($EOFTerminated && $isLast){
                $data[] = new EOFEscapeToken();
            }
        }elseif($afterPiece instanceof EscapeToken){
            $data[] = new StringBitToken($stringBit);
            $data[] = new ContinuationEscapeToken("\n");
            $data[] = new EncodedCodePointEscapeToken("@");
            $data[] = new EncodedCodePointEscapeToken("\0");
            $data[] = new CodePointEscapeToken("Fac", NULL);
            $data[] = new CodePointEscapeToken("0", NULL);
            if($EOFTerminated && $isLast){
                $data[] = new EOFEscapeToken();
            }
        }
        return $data;
    };
}
