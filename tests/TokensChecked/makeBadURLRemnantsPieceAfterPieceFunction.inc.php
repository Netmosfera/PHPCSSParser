<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked;

use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEOFEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedContinuationEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedBadURLRemnantsBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;

function makeBadURLRemnantsPieceAfterPieceFunction(Bool $EOFTerminated){
    return function($afterPiece, Bool $isLast) use($EOFTerminated){
        $remnantsBeginBit = "( start bad {} \u{2764} \" URL \u{2764} ' remnants url(";
        $remnantsBit = "bad { } \u{2764} \" URL \u{2764} ' remnants ( url(";

        $data = [];
        if($afterPiece === NULL){
            $data[] = new CheckedBadURLRemnantsBitToken($remnantsBeginBit);
            $data[] = new CheckedContinuationEscapeToken("\n");
            if($EOFTerminated && $isLast){
                $data[] = new CheckedEOFEscapeToken();
            }
        }elseif($afterPiece instanceof BadURLRemnantsBitToken){
            $data[] = new CheckedContinuationEscapeToken("\n");
            $data[] = new CheckedEncodedCodePointEscapeToken("@");
            $data[] = new CheckedEncodedCodePointEscapeToken("\0");
            $data[] = new CheckedCodePointEscapeToken("Fac", NULL);
            $data[] = new CheckedCodePointEscapeToken("0", NULL);
            if($EOFTerminated && $isLast){
                $data[] = new CheckedEOFEscapeToken();
            }
        }elseif($afterPiece instanceof EscapeToken){
            $data[] = new CheckedBadURLRemnantsBitToken($remnantsBit);
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
