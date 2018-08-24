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
 */
function NOT_A_NUMBER_CONTINUATION_AFTER_E_PART(){
    $set = new CompressedCodePointSet();
    $set->selectAll();
    $sequences = getCodePointsFromRanges($set);
    $sequences[] = " \t \n \r \r\n \f ";
    $sequences[] = "sample \u{2764} string";
    $sequences[] = "";
    return $sequences;
}
