<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokens\Misc;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSeqsSets\getWhitespaceSeqsSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSeqsSets\getNewlineSeqsSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getWhitespacesSet;
use function Netmosfera\PHPCSSASTTests\getSampleCodePointsFromRanges;
use function Netmosfera\PHPCSSASTTests\assertThrowsType;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class WhitespaceTokenTest extends TestCase
{
    public function data1(){
        $whitespaces = getWhitespaceSeqsSet();
        $newlines = getNewlineSeqsSet();
        foreach($whitespaces as $WS){
            $normalizedWS = $newlines->contains($WS) ? SpecData::NEWLINE : $WS;
            yield [$WS, $normalizedWS];
            yield ["   " . $WS, "   " . $normalizedWS];
            yield [$WS . "   ", $normalizedWS . "   "];
            yield ["   " . $WS . "   ", "   " . $normalizedWS . "   "];
            $WS = $WS . $WS;
            $normalizedWS = $normalizedWS . $normalizedWS;
            yield [$WS, $normalizedWS];
            yield ["   " . $WS, "   " . $normalizedWS];
            yield [$WS . "   ", $normalizedWS . "   "];
            yield ["   " . $WS . "   ", "   " . $normalizedWS . "   "];
        }
    }

    /** @dataProvider data1 */
    public function test1(String $WS, String $normalizedWS){
        $whitespace1 = new WhitespaceToken($WS);
        $whitespace2 = new WhitespaceToken($WS);
        $normalizedWhitespace = new WhitespaceToken($normalizedWS);
        assertMatch($whitespace1, $whitespace2);
        assertMatch((String)$whitespace1, $WS);
        assertMatch($whitespace1->normalize(), $normalizedWhitespace);
    }
}
