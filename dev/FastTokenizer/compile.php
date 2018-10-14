<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\FastTokenizer;

use function iterator_to_array;
use Netmosfera\PHPCSSASTDev\Data\CodePoint;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSeqsSets\getNewlineSeqsSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSeqsSets\getWhitespaceSeqsSet;
use function Netmosfera\PHPCSSASTDev\Data\cp;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getDigitsSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getNewlinesSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getNameItemsSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getHexDigitsSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getNonASCIIsSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getWhitespacesSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getNameStartersSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getNonPrintablesSet;
use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use Netmosfera\PHPCSSASTDev\Data\TextSet;

require __DIR__ . "/../../vendor/autoload.php";

(function(){
    //------------------------------------------------------------------------------------
    // Define tokens:
    //------------------------------------------------------------------------------------

    $groups["NAME"] = getNameItemsSet();
    $groups["NAME"]->removeAll(getNonASCIIsSet());

    $groups["NAME_STARTER"] = getNameStartersSet();
    $groups["NAME_STARTER"]->removeAll(getNonASCIIsSet());

    $groups["HEX_DIGIT"] = getHexDigitsSet();

    $groups["DIGIT"] = getDigitsSet();

    $groups["NEWLINE"] = getNewlineSeqsSet();

    $groups["WHITESPACE"] = getWhitespaceSeqsSet();

    $groups["NONPRINTABLE"] = getNonPrintablesSet();

    $delimitersAsArray = str_split("\"\\:;,{}[]()'#@+-*/%><.?!&=$^~|`");
    assert(array_unique($delimitersAsArray) === $delimitersAsArray);
    $groups["DELIMITER"] = new CompressedCodePointSet();
    foreach($delimitersAsArray as $delimiter){
        $groups["DELIMITER"]->add(cp($delimiter));
    }

    /** @var CompressedCodePointSet[]|TextSet[] $groups */

    foreach($groups as $groupName => $sequences){
        $groups[$groupName] = [];
        foreach($sequences as $sequence){
            $groups[$groupName][] = (String)$sequence;
        }
    }

    /** @var String[][] $groups */

    //------------------------------------------------------------------------------------
    // Save the data
    //------------------------------------------------------------------------------------

    $source  = "<?php declare(strict_types = 1);\n\n";
    $source .= "namespace Netmosfera\\PHPCSSAST\FastTokenizer;\n\n";
    $source .= "class Data\n";
    $source .= "{\n";

    //------------------------------------------------------------------------------------

    $groupID = 0;
    foreach($groups as $constantName => $_){
        $source .= "    public const " . $constantName . " = " . (2 ** $groupID++) . ";\n";
    }

    //------------------------------------------------------------------------------------

    $map = [];
    $classifySequences = range(chr(1), chr(127));
    $classifySequences[] = "\r\n";
    foreach($classifySequences as $sequence){
        $map[$sequence] = 0;
        $groupID = 0;
        foreach($groups as $groupName => $groupSet){
            if(in_array($sequence, $groupSet)){
                $map[$sequence] |= 2 ** $groupID;
            }
            $groupID++;
        }
    }
    $source .= "    public const CODE_POINT_TYPES = " . var_export($map, TRUE) . ";\n";

    //------------------------------------------------------------------------------------

    $source .= "}\n";

    $destinationFile = __DIR__ . "/../../src/FastTokenizer/Data.php";
    file_put_contents($destinationFile, $source);

    echo "DONE";
})();
