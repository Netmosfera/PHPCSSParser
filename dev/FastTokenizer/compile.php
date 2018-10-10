<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\FastTokenizer;

use function iterator_to_array;
use function Netmosfera\PHPCSSASTDev\Data\cp;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getDigitsSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getHexDigitsSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getNonASCIIsSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getWhitespacesSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getNameStartersSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getNonPrintablesSet;
use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;

require __DIR__ . "/../../vendor/autoload.php";

(function(){
    //------------------------------------------------------------------------------------
    // Define tokens:
    //------------------------------------------------------------------------------------

    $alphaHexDigits = getHexDigitsSet();
    $alphaHexDigits->removeAll(getDigitsSet());

    // ASCII CHARACTERS
    $groups["A"] = getNameStartersSet();
    $groups["A"]->removeAll($alphaHexDigits);
    $groups["A"]->removeAll(getNonASCIIsSet());

    // DELIMITERS
    $delimitersAsArray = str_split("\"\\:;,{}[]()'#@+-*/%><.?!&=$^~|`");
    assert(array_unique($delimitersAsArray) === $delimitersAsArray);
    $groups["S"] = new CompressedCodePointSet();
    foreach($delimitersAsArray as $delimiter){
        $groups["S"]->add(cp($delimiter));
    }

    // ALPHABETIC HEX DIGITS
    $groups["H"] = $alphaHexDigits;

    // WHITESPACE
    $groups["W"] = getWhitespacesSet();

    // DIGITS
    $groups["D"] = getDigitsSet();

    // NONPRINTABLE CHARACTERS
    $groups["I"] = getNonPrintablesSet();

    // NON-ASCII CHARACTERS
    $nonASCII = getNonASCIIsSet();

    /** @var CompressedCodePointSet[] $groups */

    verifyGroupsDoNotOverlap(array_merge($groups, [$nonASCII]));
    verifyUnicodeCovered(array_merge($groups, [$nonASCII]));

    //------------------------------------------------------------------------------------
    // Generate the recognize-token set-arrays
    //------------------------------------------------------------------------------------

    $recognizeGroups = [];
    foreach($groups as $nameOfGroup => $codePointsOfGroup){
        $recognizeGroups[$nameOfGroup] = keyze(iterator_to_array($codePointsOfGroup, FALSE));
    }

    $recognizeNonASCIIPattern = "/^[" . $nonASCII->regexp() . "]/usD";

    //------------------------------------------------------------------------------------
    // Generate the splitter
    //------------------------------------------------------------------------------------

    foreach($groups as $codePointsOfGroup){
        $splitterPieces[] = "[" . $codePointsOfGroup->regexp() . "]+";
    }
    $splitterPieces[] = "[" . $nonASCII->regexp() . "]+";
    $splitter = "/(" . implode("|", $splitterPieces) . ")/usD";

    //------------------------------------------------------------------------------------
    // Save the data
    //------------------------------------------------------------------------------------

    $source  = "<?php declare(strict_types = 1);\n\n";
    $source .= "namespace Netmosfera\\PHPCSSAST\FastTokenizer;\n\n";
    $source .= "class Data\n";
    $source .= "{\n";
    $source .= "    public const SPLIT = " . var_export($splitter, TRUE) . ";\n";
    $source .= "    public const RECOGNIZE_GROUPS = " . var_export($recognizeGroups, TRUE) . ";\n";
    $source .= "    public const RECOGNIZE_NON_ASCII = " . var_export($recognizeNonASCIIPattern, TRUE) . ";\n";
    $source .= "}\n";

    $destinationFile = __DIR__ . "/../../src/FastTokenizer/Data.php";
    file_put_contents($destinationFile, $source);

    echo "DONE";
})();
