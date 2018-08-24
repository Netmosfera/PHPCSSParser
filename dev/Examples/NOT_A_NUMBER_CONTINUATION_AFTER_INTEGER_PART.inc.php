<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\Examples;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use function Netmosfera\PHPCSSASTTests\getCodePointsFromRanges;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * -
 *
 * Any CodePoint sequence:
 *
 * - not starting with a digit
 * - not starting with "." and a digit
 * - not starting with "e|E" and a digit
 * - not starting with "e|E" and "+|-" and a digit
 */
function NOT_A_NUMBER_CONTINUATION_AFTER_INTEGER_PART(){
    $set = new CompressedCodePointSet();
    $set->selectAll();
    $sequences = getCodePointsFromRanges($set);
    $sequences[] = " \t \n \r \r\n \f ";
    $sequences[] = "sample \u{2764} string";
    $sequences[] = ".a";
    $sequences[] = "ea";
    $sequences[] = "Ea";
    $sequences[] = "e+a";
    $sequences[] = "E+a";
    $sequences[] = "";
    return $sequences;
}
