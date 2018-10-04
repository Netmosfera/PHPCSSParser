<?php declare(strict_types = 1);

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
use Netmosfera\PHPCSSAST\SpecData;

require __DIR__ . "/../../vendor/autoload.php";

$MAKEMAP = [];

$MAKEMAP["DIGITS_REGEX_SET"] = getDigitsSet()->regexp();
SpecData::DIGITS_REGEX_SET;

$MAKEMAP["HEX_DIGITS_REGEX_SET"] = getHexDigitsSet()->regexp();
SpecData::HEX_DIGITS_REGEX_SET;

$MAKEMAP["NAME_STARTERS_REGEX_SET"] = getNameStartersSet()->regexp();
SpecData::NAME_STARTERS_REGEX_SET;

$MAKEMAP["NAME_COMPONENTS_REGEX_SET"] = getNameItemsSet()->regexp();
SpecData::NAME_COMPONENTS_REGEX_SET;

$MAKEMAP["WHITESPACES_REGEX_SET"] = getWhitespacesSet()->regexp();
SpecData::WHITESPACES_REGEX_SET;

$MAKEMAP["WHITESPACES_REGEX_SEQS"] = getWhitespaceSeqsSet()->getRegExp();
SpecData::WHITESPACES_REGEX_SEQS;

$MAKEMAP["NEWLINES_REGEX_SET"] = getNewlinesSet()->regexp();
SpecData::NEWLINES_REGEX_SET;

$MAKEMAP["NEWLINES_REGEX_SEQS"] = getNewlineSeqsSet()->getRegExp();
SpecData::NEWLINES_REGEX_SEQS;

$MAKEMAP["ENCODED_CP_ESCAPE_REGEX_SET"] = getEncodedCodePointEscapeSet()->regexp();
SpecData::ENCODED_CP_ESCAPE_REGEX_SET;

$MAKEMAP["STRING_BIT_CPS_REGEX_SET"] = getStringBitSet()->regexp();
SpecData::STRING_BIT_CPS_REGEX_SET;

$MAKEMAP["URL_TOKEN_BIT_CPS_REGEX_SET"] = getURLTokenBitSet()->regexp();
SpecData::URL_TOKEN_BIT_CPS_REGEX_SET;

$MAKEMAP["URL_TOKEN_BIT_NOT_CPS_REGEX_SET"] = getURLTokenBitDisallowedSet()->regexp();
SpecData::URL_TOKEN_BIT_NOT_CPS_REGEX_SET;

$MAKEMAP["BAD_URL_REMNANTS_BIT_CPS_REGEX_SET"] = getBadURLRemnantsBitSet()->regexp();
SpecData::BAD_URL_REMNANTS_BIT_CPS_REGEX_SET;

$MAKEMAP["NEWLINE"] = (String)cp("\n");
SpecData::NEWLINE;

$MAKEMAP["REPLACEMENT_CHARACTER"] = (String)cp("\u{FFFD}");
SpecData::REPLACEMENT_CHARACTER;

$keyLength = strlen(array_reduce(array_keys($MAKEMAP), function($a, $b){
    return strlen($a) > strlen($b) ? $a : $b;
}, ""));

$fields = [];
foreach($MAKEMAP as $name => $value){
    $field  = "    ";
    $field .= "public const ";
    $field .= str_pad($name, $keyLength, " ", STR_PAD_RIGHT);
    $field .= " = " . var_export($value, TRUE) . ";";
    $fields[] = $field;
}

$source  = "<?php declare(strict_types = 1);\n\n";
$source .= "// phpcs:disable\n\n";
$source .= "namespace Netmosfera\\PHPCSSAST;\n\n";
$source .= "class SpecData\n";
$source .= "{\n";
$source .= implode("\n", $fields) . "\n";
$source .= "}\n\n";
$source .= "// phpcs:enable\n";

$destinationFile = __DIR__ . "/../../src/SpecData.php";
file_put_contents($destinationFile, $source);

echo "DONE";
