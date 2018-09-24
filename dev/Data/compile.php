<?php declare(strict_types = 1); // atom

use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getDigitsSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getEncodedEscapeSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getLettersSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getNewlinesSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getNonASCIIsSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getNameItemsSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getHexDigitsSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getUCLettersSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getLCLettersSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getWhitespacesSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getNameStartersSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getNonPrintablesSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getStringDelimiterSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSeqsSets\getNewlineSeqsSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSeqsSets\getWhitespaceSeqsSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getValidEscapeStartersSet;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

require __DIR__ . "/../../vendor/autoload.php";

$data = (object)[];

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

$data->DIGITS_SET = getDigitsSet()->getRegExp();

$data->HEX_DIGITS_SET = getHexDigitsSet()->getRegExp();

$data->LETTERS_SET = getLettersSet()->getRegExp();
$data->LETTERS_LC_SET = getLCLettersSet()->getRegExp();
$data->LETTERS_UC_SET = getUCLettersSet()->getRegExp();

$data->NAME_STARTERS_SET = getNameStartersSet()->getRegExp();
$data->NAME_ITEMS_SET = getNameItemsSet()->getRegExp();

$data->WHITESPACES_SET = getWhitespacesSet()->getRegExp();
$data->WHITESPACES_SEQS_SET = getWhitespaceSeqsSet()->getRegExp();

$data->NEWLINES_SET = getNewlinesSet()->getRegExp();
$data->NEWLINES_SEQS_SET = getNewlineSeqsSet()->getRegExp();

$data->NON_ASCII_SET = getNonASCIIsSet()->getRegExp();

$data->NON_PRINTABLES_SET = getNonPrintablesSet()->getRegExp();

$data->STRING_DELIMITERS_SET = getStringDelimiterSet()->getRegExp();

$data->VALID_ESCAPE_STARTERS_SET = getValidEscapeStartersSet()->getRegExp();
$data->ENCODED_ESCAPE_SET = getEncodedEscapeSet()->getRegExp();

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

$data = (array)$data;

$keys = array_keys($data);
$keyLength = strlen(array_reduce($keys, function ($a, $b){
    return strlen($a) > strlen($b) ? $a : $b; }
, ""));

$fields = [];
foreach($data as $name => $value){
    $field  = "    public const ";
    $field .= str_pad($name, $keyLength, " ", STR_PAD_RIGHT);
    $field .= " = " . var_export($value, TRUE) . ";";
    $fields[] = $field;
}

$source  = "<?php declare(strict_types = 1); // atom\n\n";
$source .= "namespace Netmosfera\\PHPCSSAST;\n\n";
$source .= "//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]\n\n";
$source .= "class SpecData\n";
$source .= "{\n" . implode("\n", $fields) . "\n}\n";

file_put_contents(__DIR__ . "/../../src/SpecData.php", $source);
