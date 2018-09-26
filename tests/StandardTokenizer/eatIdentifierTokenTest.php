<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Escapes\Escape;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;
use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\CodePointEscape;
use function Netmosfera\PHPCSSAST\StandardTokenizer\eatIdentifierToken;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function array_unshift;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * E = valid escape
 * N = name code point
 * S = name start code point
 * T = any sequence of zero or more name code points or valid escapes
 *
 *    |  REQUIRED  |
 * -----------------------------------------------------------
 * #1 |     --     | [T] | [^NE]... | RETURNS --[T]
 *
 * #2 |  - |       |     | [^SE]... | RETURNS NULL
 * #3 |  - | [S]   | [T] | [^NE]... | RETURNS -[S][T]
 * #4 |  - | [E]   | [T] | [^NE]... | RETURNS -[E][T]
 *
 * #5 |    |       |     | [^SE]... | RETURNS NULL
 * #6 |    | [S]   | [T] | [^NE]... | RETURNS [S][T]
 * #7 |    | [E]   | [T] | [^NE]... | RETURNS [E][T]
 */
class eatIdentifierTokenTest extends TestCase
{
    function data1(){
        return cartesianProduct(
            ANY_UTF8(),
            makePiecesSample(Closure::fromCallable([$this, "getPieces"])),
            ["", "XXXX"]
        );
    }

    /** @dataProvider data1 */
    function test1(String $prefix, Array $pieces, String $rest){
        if($pieces === []){
            $pieces = [new NameBitToken("--")];
        }elseif($pieces[0] instanceof NameBitToken){
            $pieces[0] = new NameBitToken("--" . $pieces[0]);
        }else{
            array_unshift($pieces, new NameBitToken("--"));
        }
        $traverser = getTraverser($prefix, implode("", $pieces) . $rest);
        $expected = new IdentifierToken(new NameToken($pieces));
        $eatEscape = function(Traverser $traverser): ?Escape{
            return $traverser->eatStr("\\@") === NULL ? NULL : new CodePointEscape("@");
        };
        $actual = eatIdentifierToken($traverser, "S", "N", $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data2(){
        return cartesianProduct(ANY_UTF8(), ["", "XXXX"]);
    }

    /** @dataProvider data2 */
    function test2(String $prefix, String $rest){
        $traverser = getTraverser($prefix, "-" . $rest);
        $expected = NULL;
        $eatEscape = function(Traverser $traverser): ?Escape{ return NULL; };
        $actual = eatIdentifierToken($traverser, "S", "N", $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), "-" . $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data3(){
        return cartesianProduct(
            ANY_UTF8(),
            makePiecesSample(Closure::fromCallable([$this, "getPieces"])),
            ["", "XXXX"]);
    }

    /** @dataProvider data3 */
    function test3(String $prefix, Array $pieces, String $rest){
        if($pieces === []){
            $pieces = [new NameBitToken("-S")];
        }elseif($pieces[0] instanceof NameBitToken){
            $pieces[0] = new NameBitToken("-S" . $pieces[0]);
        }else{
            array_unshift($pieces, new NameBitToken("-S"));
        }
        $traverser = getTraverser($prefix, implode("", $pieces) . $rest);
        $expected = new IdentifierToken(new NameToken($pieces));
        $eatEscape = function(Traverser $traverser): ?Escape{
            return $traverser->eatStr("\\@") === NULL ? NULL : new CodePointEscape("@");
        };
        $actual = eatIdentifierToken($traverser, "S", "N", $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data4(){
        return cartesianProduct(
            ANY_UTF8(),
            makePiecesSample(Closure::fromCallable([$this, "getPieces"])),
            ["", "XXXX"]
        );
    }

    /** @dataProvider data4 */
    function test4(String $prefix, Array $pieces, String $rest){
        array_unshift($pieces, new NameBitToken("-"), new CodePointEscape("@"));
        $traverser = getTraverser($prefix, implode("", $pieces) . $rest);
        $expected = new IdentifierToken(new NameToken($pieces));
        $eatEscape = function(Traverser $traverser): ?Escape{
            return $traverser->eatStr("\\@") === NULL ? NULL : new CodePointEscape("@");
        };
        $actual = eatIdentifierToken($traverser, "S", "N", $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data5(){
        return cartesianProduct(ANY_UTF8(), ["", "XXXX"]);
    }

    /** @dataProvider data5 */
    function test5(String $prefix, String $rest){
        $traverser = getTraverser($prefix, $rest);
        $expected = NULL;
        $eatEscape = function(Traverser $traverser): ?Escape{ return NULL; };
        $actual = eatIdentifierToken($traverser, "S", "N", $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data6(){
        return cartesianProduct(
            ANY_UTF8(),
            makePiecesSample(Closure::fromCallable([$this, "getPieces"])),
            ["", "XXXX"]
        );
    }

    /** @dataProvider data6 */
    function test6(String $prefix, Array $pieces, String $rest){
        if($pieces === []){
            $pieces = [new NameBitToken("S")];
        }elseif($pieces[0] instanceof NameBitToken){
            $pieces[0] = new NameBitToken("S" . $pieces[0]);
        }
        else{
            array_unshift($pieces, new NameBitToken("S"));
        }
        $traverser = getTraverser($prefix, implode("", $pieces) . $rest);
        $expected = new IdentifierToken(new NameToken($pieces));
        $eatEscape = function(Traverser $traverser): ?Escape{
            return $traverser->eatStr("\\@") === NULL ? NULL : new CodePointEscape("@");
        };
        $actual = eatIdentifierToken($traverser, "S", "N", $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data7(){
        return cartesianProduct(
            ANY_UTF8(),
            makePiecesSample(Closure::fromCallable([$this, "getPieces"])),
            ["", "XXXX"]
        );
    }

    /** @dataProvider data7 */
    function test7(String $prefix, Array $pieces, String $rest){
        array_unshift($pieces, new CodePointEscape("@"));
        $traverser = getTraverser($prefix, implode("", $pieces) . $rest);
        $expected = new IdentifierToken(new NameToken($pieces));
        $eatEscape = function(Traverser $traverser): ?Escape{
            return $traverser->eatStr("\\@") === NULL ? NULL : new CodePointEscape("@");
        };
        $actual = eatIdentifierToken($traverser, "S", "N", $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function getPieces($afterPiece){
        if(!$afterPiece instanceof NameBitToken){
            $data[] = new NameBitToken("N");
            $data[] = new NameBitToken("NN");
            $data[] = new NameBitToken("NNN");
        }
        $data[] = new CodePointEscape("@");
        return $data;
    }
}
