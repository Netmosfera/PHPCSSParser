<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Misc;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedWhitespaceToken;
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
 * #2 | test invalid
 */
class WhitespaceTokenTest extends TestCase
{
    public function data1(){
        $whitespaces = getWhitespaceSeqsSet();
        $newlines = getNewlineSeqsSet();
        foreach($whitespaces as $WS){
            $normalizedWS = $newlines->contains($WS) ? SpecData::$instance->NEWLINE : $WS;
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
        $whitespace1 = new CheckedWhitespaceToken($WS);
        $whitespace2 = new CheckedWhitespaceToken($WS);
        $normalizedWhitespace = new CheckedWhitespaceToken($normalizedWS);
        assertMatch($whitespace1, $whitespace2);
        assertMatch((String)$whitespace1, $WS);
        assertMatch($whitespace1->normalize(), $normalizedWhitespace);
    }

    public function data2(){
        $set = new CompressedCodePointSet();
        $set->selectAll();
        $set->removeAll(getWhitespacesSet());
        foreach(getSampleCodePointsFromRanges($set) as $codePoint){
            yield [$codePoint];
            yield ["   " . $codePoint];
            yield [$codePoint . "   "];
            yield ["   " . $codePoint . "   "];
        }
    }

    /** @dataProvider data2 */
    public function test2(String $value){
        assertThrowsType(InvalidToken::CLASS, function() use($value){
            new CheckedWhitespaceToken($value);
        });
    }
}
