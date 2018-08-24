<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSAST\Tokenizer\eatStringToken;
use function Netmosfera\PHPCSSASTTests\getCodePointsFromRanges;
use function Netmosfera\PHPCSSASTDev\SpecData\CodePointSets\getStringDelimiterSet;
use function Netmosfera\PHPCSSASTDev\SpecData\CodePointSeqsSets\getNewlineSeqsSet;
use Netmosfera\PHPCSSAST\Tokens\Strings\ActualEscape;
use Netmosfera\PHPCSSAST\Tokens\Strings\PlainEscape;
use Netmosfera\PHPCSSAST\Tokens\BadStringToken;
use Netmosfera\PHPCSSAST\Tokens\StringToken;
use Netmosfera\PHPCSSAST\Traverser;
use PHPUnit\Framework\TestCase;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class eatStringTokenTest extends TestCase
{
    function piecesToString(Array $pieces){
        // @TODO use an actual printer/formatter for this instead
        $string = "";
        foreach($pieces as $piece){
            if($piece instanceof ActualEscape){
                $string .= "\\" . $piece->hexDigits . ($piece->whitespace ?? "");
            }elseif($piece instanceof PlainEscape){
                $string .= "\\" . $piece->codePoint;
            }else{
                $string .= $piece;
            }
        }
        return $string;
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_misc(){
        return cartesianProduct(
            ANY_UTF8(),
            getCodePointsFromRanges(getStringDelimiterSet()),
            ANY_UTF8()
        );
    }

    /** @dataProvider data_misc */
    function test_misc($prefix, $delimiter, $rest){
        // Test simple string with a non-ASCII character in it.
        $pieces[] = " hello \u{2764} world ";

        // The whitespace is a terminator only after a unicode code point:
        $pieces[] = new ActualEscape("abCdEf", "\r");
        $pieces[] = new ActualEscape("12345",  "\n");
        $pieces[] = new ActualEscape("bCdEf",  "\r\n");
        $pieces[] = new ActualEscape("1234",   "\f");
        $pieces[] = new ActualEscape("dEf",    "\t");
        $pieces[] = new ActualEscape("Ef",     " ");

        $pieces[] = " hello \u{2764} world ";

        // It is optional however:
        $pieces[] = new ActualEscape("abCdEf", NULL);
        $pieces[] = new ActualEscape("12345",  NULL);
        $pieces[] = new ActualEscape("bCdEf",  NULL);
        $pieces[] = new ActualEscape("1234",   NULL);
        $pieces[] = new ActualEscape("dEf",    NULL);
        $pieces[] = new ActualEscape("Ef",     NULL);
        $pieces[] = "<- does not start with a whitespace; otherwise it will get eaten by the previous piece";

        // The whitespace is not a terminator in plain escapes
        $pieces[] = new PlainEscape("x"); $pieces[] = " ";
        $pieces[] = new PlainEscape("y"); $pieces[] = "\t";
        $pieces[] = new PlainEscape("z"); $pieces[] = " ";

        // The plain escapes can be adjacent
        $pieces[] = new PlainEscape("x");
        $pieces[] = new PlainEscape("y");
        $pieces[] = new PlainEscape("z");

        // The plain escapes may be used to insert characters normally not allowed in strings
        $pieces[] = new PlainEscape("\r");
        $pieces[] = new PlainEscape("\n");
        $pieces[] = new PlainEscape("\r\n");
        $pieces[] = new PlainEscape("\f");
        $pieces[] = new PlainEscape("\\");
        $pieces[] = new PlainEscape("\"");
        $pieces[] = new PlainEscape("'");

        $string = $this->piecesToString($pieces);

        $t = new Traverser($prefix . $delimiter . $string . $delimiter . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatStringToken($t), new StringToken($pieces)));
        self::assertTrue(match($t->eatAll(), $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_unterminated_EOF(){
        return cartesianProduct(
            ANY_UTF8(),
            getCodePointsFromRanges(getStringDelimiterSet()),
            ["", "skip \u{2764} me"]
        );
    }

    /** @dataProvider data_unterminated_EOF */
    function test_unterminated_EOF($prefix, $delimiter, $string){
        $t = new Traverser($prefix . $delimiter . $string, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatStringToken($t), new StringToken($string === "" ? [] : [$string], TRUE)));
        self::assertTrue(match($t->eatAll(), ""));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_unterminated_newline(){
        return cartesianProduct(
            ANY_UTF8(),
            getCodePointsFromRanges(getStringDelimiterSet()),
            ["", "skip \u{2764} me"],
            getNewlineSeqsSet(),
            ANY_UTF8()
        );
    }

    /** @dataProvider data_unterminated_newline */
    function test_unterminated_newline($prefix, $delimiter, $string, $newline, $rest){
        $t = new Traverser($prefix . $delimiter . $string . $newline . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatStringToken($t), new BadStringToken($string === "" ? [] : [$string])));
        self::assertTrue(match($t->eatAll(), $newline . $rest));
    }
}
