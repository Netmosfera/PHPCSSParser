<?php declare(strict_types = 1);

use Netmosfera\PHPCSSASTDev\Data\CodePoint;
use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use Netmosfera\PHPCSSASTDev\Data\ContiguousCodePointsSet;
use function Netmosfera\PHPCSSASTDev\Data\cp;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getDigitsSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getNewlinesSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getNameItemsSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getHexDigitsSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getStringBitSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getURLTokenBitSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getWhitespacesSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getNameStartersSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSeqsSets\getNewlineSeqsSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getBadURLRemnantsBitSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSeqsSets\getWhitespaceSeqsSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getURLTokenBitDisallowedSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getEncodedCodePointEscapeSet;

require __DIR__ . "/../../vendor/autoload.php";

$multibytes = new CompressedCodePointSet();
$multibytes->selectAll();
$multibytes->removeAll(new ContiguousCodePointsSet(new CodePoint(0), new CodePoint(255)));

$MAKEMAP = [];

$MAKEMAP["DIGITS_REGEX_SET"] = getDigitsSet()->regexp();

$MAKEMAP["HEX_DIGITS_REGEX_SET"] = getHexDigitsSet()->regexp();

$MAKEMAP["NAME_STARTERS_REGEX_SET"] = getNameStartersSet()->regexp();

$MAKEMAP["NAME_COMPONENTS_REGEX_SET"] = getNameItemsSet()->regexp();

$nsb = getNameStartersSet();
$nsb->removeAll($multibytes);
$MAKEMAP["NAME_STARTERS_BYTES_REGEX_SET"] = $nsb->regexp();

$nsb = getNameItemsSet();
$nsb->removeAll($multibytes);
$MAKEMAP["NAME_COMPONENTS_BYTES_REGEX_SET"] = $nsb->regexp();

$MAKEMAP["WHITESPACES_REGEX_SET"] = getWhitespacesSet()->regexp();

$MAKEMAP["WHITESPACES_REGEX_SEQS"] = getWhitespaceSeqsSet()->getRegExp();

$MAKEMAP["NEWLINES_REGEX_SET"] = getNewlinesSet()->regexp();

$MAKEMAP["NEWLINES_REGEX_SEQS"] = getNewlineSeqsSet()->getRegExp();

$MAKEMAP["ENCODED_CP_ESCAPE_REGEX_SET"] = getEncodedCodePointEscapeSet()->regexp();

$MAKEMAP["STRING_BIT_CPS_REGEX_SET"] = getStringBitSet()->regexp();

$MAKEMAP["URL_TOKEN_BIT_CPS_REGEX_SET"] = getURLTokenBitSet()->regexp();

$MAKEMAP["URL_TOKEN_BIT_NOT_CPS_REGEX_SET"] = getURLTokenBitDisallowedSet()->regexp();

$MAKEMAP["BAD_URL_REMNANTS_BIT_CPS_REGEX_SET"] = getBadURLRemnantsBitSet()->regexp();

$MAKEMAP["NEWLINE"] = (String)cp("\n");

$MAKEMAP["REPLACEMENT_CHARACTER"] = (String)cp("\u{FFFD}");

$keyLength = strlen(array_reduce(array_keys($MAKEMAP), function($a, $b){
    return strlen($a) > strlen($b) ? $a : $b;
}, ""));

$fields = [];
foreach($MAKEMAP as $name => $value){
    $field  = "    ";
    $field .= "public $";
    $field .= str_pad($name, $keyLength, " ", STR_PAD_RIGHT);
    $field .= " = " . var_export($value, TRUE) . ";";
    $fields[] = $field;
}

$source  = "<?php declare(strict_types = 1);\n\n";
$source .= "// phpcs:disable\n\n";
$source .= "namespace Netmosfera\\PHPCSSAST;\n\n";
$source .= "class SpecData\n";
$source .= "{\n";
$source .= implode("\n", $fields) . "\n\n";
$source .= "    public static \$instance;\n";
$source .= "}\n\n";
$source .= "SpecData::\$instance = new SpecData();\n\n";
$source .= "// phpcs:enable\n";

$destinationFile = __DIR__ . "/../../src/SpecData.php";
file_put_contents($destinationFile, $source);

echo "DONE";
