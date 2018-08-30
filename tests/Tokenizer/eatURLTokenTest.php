<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use Error;
use function Netmosfera\PHPCSSASTDev\SpecData\CodePointSets\getNonPrintablesSet;
use function Netmosfera\PHPCSSASTDev\SpecData\CodePointSets\getWhitespacesSet;
use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Traverser;
use Netmosfera\PHPCSSAST\Tokens\URLToken;
use Netmosfera\PHPCSSAST\Tokens\BadURLToken;
use Netmosfera\PHPCSSAST\Tokens\SubTokens\PlainEscape;
use Netmosfera\PHPCSSAST\Tokens\SubTokens\ActualEscape;
use Netmosfera\PHPCSSAST\Tokens\SubTokens\BadURLRemnants;
use function Netmosfera\PHPCSSAST\Tokenizer\eatURLToken;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1  |                EOF
 * #2  |                ) + rest
 * #3  |                invalid escape -> remnants
 * #4  |                valid escape
 * #5  |                blacklisted code point -> remnants
 * #6  |                sequence
 * #7  |     sequence + EOF
 * #8  |     sequence + ) + rest
 * #9  |     sequence + invalid escape -> remnants
 * #10 |     sequence + valid escape
 * #11 |     sequence + blacklisted code point -> remnants
 * #12 |     sequence + ws + not ) or EOF
 *
 * @TODO:
 * #13 |     sequence + EOF
 * #14 |     sequence + ) + rest
 * #15 |     sequence + invalid escape -> remnants
 * #16 |     sequence + valid escape
 * #17 |     sequence + blacklisted code point -> remnants
 * #18 |     sequence + ws + not ) or EOF
 */
class eatURLTokenTest extends TestCase
{
    function getTraverser(String $prefix, String $data){
        $t = new Traverser($prefix . $data, TRUE);
        $t->eatStr($prefix);
        return $t;
    }

    function URLify(Array $pieces){
        $URL = "";
        foreach($pieces as $piece){
            if(is_string($piece)){
                $URL .= $piece;
            }elseif($piece instanceof PlainEscape){
                $URL .= "\\" . $piece->codePoint;
            }elseif($piece instanceof ActualEscape){
                $URL .= "\\" . $piece->hexDigits . $piece->whitespace;
            }else{
                throw new Error();
            }
        }
        return $URL;
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_bigURL(){
        return cartesianProduct(
            ANY_UTF8(),
            ["\f", ""]
        );
    }

    /** @dataProvider data_bigURL */
    function test_bigURL($prefix, $startWS){
        $pieces[] = "http";
        $pieces[] = new PlainEscape(":");
        $pieces[] = new PlainEscape("/");
        $pieces[] = new PlainEscape("/");
        $pieces[] = "www.example.org/";
        $pieces[] = new PlainEscape("\\");
        $pieces[] = new ActualEscape("FFAACC", "\n");
        $pieces[] = "path/path";
        $pieces[] = new PlainEscape("/");
        $pieces[] = new PlainEscape("?");
        $pieces[] = "query=string";

        $t = $this->getTraverser($prefix, $startWS . $this->URLify($pieces) . ")");

        $expected = new URLToken($startWS, $pieces, FALSE, "");

        $eatRemnants = function(Traverser $t){
            self::fail();
        };

        $actual = eatURLToken(
            $t,
            getWhitespacesSet()->getRegExp(),
            getNonPrintablesSet()->getRegExp(),
            $eatRemnants,
            Closure::fromCallable("Netmosfera\\PHPCSSAST\\Tokenizer\\Tools\\eatEscape")
        );

        assertMatch($actual, $expected);
        assertMatch($t->eatAll(), "");
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_1(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""]);
    }

    /** @dataProvider data_1 */
    function test_1($prefix, $startWS){
        $t = $this->getTraverser($prefix, $startWS);

        $expected = new URLToken($startWS, [], TRUE, "");

        $eatRemnants = function(Traverser $t){
            self::fail();
        };
        $eatEscape = function(Traverser $t){
            self::fail();
        };
        $actual = eatURLToken($t, "\f", "", $eatRemnants, $eatEscape);

        assertMatch($actual, $expected);
        assertMatch($t->eatAll(), "");
    }


    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_2(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ANY_UTF8());
    }

    /** @dataProvider data_2 */
    function test_2($prefix, $startWS, $rest){
        $t = $this->getTraverser($prefix, $startWS . ")" . $rest);

        $expected = new URLToken($startWS, [], FALSE, "");

        $eatRemnants = function(Traverser $t){
            self::fail();
        };
        $eatEscape = function(Traverser $t){
            self::fail();
        };
        $actual = eatURLToken($t, "\f", "", $eatRemnants, $eatEscape);

        assertMatch($actual, $expected);
        assertMatch($t->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_3(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ANY_UTF8());
    }

    /** @dataProvider data_3 */
    function test_3($prefix, $startWS, $rest){
        $ie = "\n invalid escape";

        $t = $this->getTraverser($prefix, $startWS . "\\" . $ie . $rest);

        $remnants = new BadURLRemnants(["irrelevant"]);
        $expected = new BadURLToken($startWS, [], $remnants);

        $eatRemnants = function(Traverser $t) use($remnants, $ie){
            assertMatch($t->eatStr("\\" . $ie), "\\" . $ie);
            return $remnants;
        };
        $eatEscape = function(Traverser $t) use($ie){
            assertMatch($t->eatStr($ie), $ie);
            return NULL;
        };
        $actual = eatURLToken($t, "\f", "0-9", $eatRemnants, $eatEscape);

        assertMatch($actual, $expected);
        assertMatch($t->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_4(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ANY_UTF8());
    }

    /** @dataProvider data_4 */
    function test_4($prefix, $startWS, $rest){
        $ve = "66ff";

        $t = $this->getTraverser($prefix, $startWS . "\\" . $ve . ")" . $rest);

        $escape = new ActualEscape($ve, NULL);
        $expected = new URLToken($startWS, [$escape], FALSE, "");

        $eatRemnants = function(Traverser $t){
            self::fail();
        };
        $eatEscape = function(Traverser $t) use($escape, $ve){
            assertMatch($t->eatStr($ve), $ve);
            return $escape;
        };
        $actual = eatURLToken($t, "\f", "0-9", $eatRemnants, $eatEscape);

        assertMatch($actual, $expected);
        assertMatch($t->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_5(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ANY_UTF8());
    }

    /** @dataProvider data_5 */
    function test_5($prefix, $startWS, $rest){
        $ic = "6 invalid code point";

        $t = $this->getTraverser($prefix, $startWS . $ic . $rest);

        $remnants = new BadURLRemnants(["irrelevant"]);
        $expected = new BadURLToken($startWS, [], $remnants);

        $eatRemnants = function(Traverser $t) use($remnants, $ic){
            assertMatch($t->eatStr($ic), $ic);
            return $remnants;
        };
        $eatEscape = function(Traverser $t){
            self::fail();
        };
        $actual = eatURLToken($t, "\f", "0-9", $eatRemnants, $eatEscape);

        assertMatch($actual, $expected);
        assertMatch($t->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_6(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ANY_UTF8());
    }

    /** @dataProvider data_6 */
    function test_6($prefix, $startWS, $rest){
        $vs = "valid sequence";

        $t = $this->getTraverser($prefix, $startWS . $vs . ")" . $rest);

        $expected = new URLToken($startWS, [$vs], FALSE, "");

        $eatRemnants = function(Traverser $t){
            self::fail();
        };
        $eatEscape = function(Traverser $t){
            self::fail();
        };
        $actual = eatURLToken($t, "\f", "0-9", $eatRemnants, $eatEscape);

        assertMatch($actual, $expected);
        assertMatch($t->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_7(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ["\f", ""]);
    }

    /** @dataProvider data_7 */
    function test_7($prefix, $startWS, $endWS){
        $vs = "valid sequence";

        $t = $this->getTraverser($prefix, $startWS . $vs . $endWS);

        $expected = new URLToken($startWS, [$vs], TRUE, $endWS);

        $eatRemnants = function(Traverser $t){
            self::fail();
        };
        $eatEscape = function(Traverser $t){
            self::fail();
        };
        $actual = eatURLToken($t, "\f", "0-9", $eatRemnants, $eatEscape);

        assertMatch($actual, $expected);
        assertMatch($t->eatAll(), "");
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_8(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ["\f", ""], ANY_UTF8());
    }

    /** @dataProvider data_8 */
    function test_8($prefix, $startWS, $endWS, $rest){
        $vs = "valid sequence";

        $t = $this->getTraverser($prefix, $startWS . $vs . $endWS . ")" . $rest);

        $expected = new URLToken($startWS, [$vs], FALSE, $endWS);

        $eatRemnants = function(Traverser $t){
            self::fail();
        };
        $eatEscape = function(Traverser $t){
            self::fail();
        };
        $actual = eatURLToken($t, "\f", "0-9", $eatRemnants, $eatEscape);

        assertMatch($actual, $expected);
        assertMatch($t->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_9(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ANY_UTF8());
    }

    /** @dataProvider data_9 */
    function test_9($prefix, $startWS, $rest){
        $vs = "valid sequence";
        $ie = "\n invalid escape";

        $t = $this->getTraverser($prefix, $startWS . $vs . "\\" . $ie . $rest);

        $remnants = new BadURLRemnants(["irrelevant"]);
        $expected = new BadURLToken($startWS, [$vs], $remnants);

        $eatRemnants = function(Traverser $t) use($remnants, $ie){
            assertMatch($t->eatStr("\\" . $ie), "\\" . $ie);
            return $remnants;
        };
        $eatEscape = function(Traverser $t) use($ie){
            assertMatch($t->eatStr($ie), $ie);
            return NULL;
        };
        $actual = eatURLToken($t, "\f", "0-9", $eatRemnants, $eatEscape);

        assertMatch($actual, $expected);
        assertMatch($t->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_10(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ANY_UTF8());
    }

    /** @dataProvider data_10 */
    function test_10($prefix, $startWS, $rest){
        $vs = "valid sequence";
        $ve = "66ff";

        $t = $this->getTraverser($prefix, $startWS . $vs . "\\" . $ve . ")" . $rest);

        $escape = new ActualEscape($ve, NULL);
        $expected = new URLToken($startWS, [$vs, $escape], FALSE, "");

        $eatRemnants = function(Traverser $t){
            self::fail();
        };
        $eatEscape = function(Traverser $t) use($escape, $ve){
            assertMatch($t->eatStr($ve), $ve);
            return $escape;
        };
        $actual = eatURLToken($t, "\f", "0-9", $eatRemnants, $eatEscape);

        assertMatch($actual, $expected);
        assertMatch($t->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_11(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ANY_UTF8());
    }

    /** @dataProvider data_11 */
    function test_11($prefix, $startWS, $rest){
        $vs = "valid sequence";
        $ic = "6 invalid code point";

        $t = $this->getTraverser($prefix, $startWS . $vs . $ic . $rest);

        $remnants = new BadURLRemnants(["irrelevant"]);
        $expected = new BadURLToken($startWS, [$vs], $remnants);

        $eatRemnants = function(Traverser $t) use($remnants, $ic){
            assertMatch($t->eatStr($ic), $ic);
            return $remnants;
        };
        $eatEscape = function(Traverser $t){
            self::fail();
        };
        $actual = eatURLToken($t, "\f", "0-9", $eatRemnants, $eatEscape);

        assertMatch($actual, $expected);
        assertMatch($t->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_12(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ANY_UTF8());
    }

    /** @dataProvider data_11 */
    function test_12($prefix, $startWS, $rest){
        $vs = "valid sequence";

        $t = $this->getTraverser($prefix, $startWS . $vs . "\f remnants" . $rest);

        $remnants = new BadURLRemnants(["irrelevant"]);
        $expected = new BadURLToken($startWS, [$vs], $remnants);

        $eatRemnants = function(Traverser $t) use($remnants){
            assertMatch($t->eatStr("\f remnants"), "\f remnants");
            return $remnants;
        };
        $eatEscape = function(Traverser $t){
            self::fail();
        };
        $actual = eatURLToken($t, "\f", "0-9", $eatRemnants, $eatEscape);

        assertMatch($actual, $expected);
        assertMatch($t->eatAll(), $rest);
    }
}
