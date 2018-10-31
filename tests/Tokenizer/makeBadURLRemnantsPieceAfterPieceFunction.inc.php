<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

use Netmosfera\PHPCSSAST\Tokens\Escapes\CodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ContinuationEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EncodedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EOFEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsBitToken;

function makeBadURLRemnantsPieceAfterPieceFunction(Bool $EOFTerminated){
    return function($afterPiece, Bool $isLast) use($EOFTerminated){
        $remnantsBeginBit = "( start bad {} \u{2764} \" URL \u{2764} ' remnants url(";
        $remnantsBit = "bad { } \u{2764} \" URL \u{2764} ' remnants ( url(";

        $data = [];
        if($afterPiece === NULL){
            $data[] = new BadURLRemnantsBitToken($remnantsBeginBit);
            $data[] = new ContinuationEscapeToken("\n");
            if($EOFTerminated && $isLast){
                $data[] = new EOFEscapeToken();
            }
        }elseif($afterPiece instanceof BadURLRemnantsBitToken){
            $data[] = new ContinuationEscapeToken("\n");
            $data[] = new EncodedCodePointEscapeToken("@");
            $data[] = new EncodedCodePointEscapeToken("\0");
            $data[] = new CodePointEscapeToken("Fac", NULL);
            $data[] = new CodePointEscapeToken("0", NULL);
            if($EOFTerminated && $isLast){
                $data[] = new EOFEscapeToken();
            }
        }elseif($afterPiece instanceof EscapeToken){
            $data[] = new BadURLRemnantsBitToken($remnantsBit);
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
