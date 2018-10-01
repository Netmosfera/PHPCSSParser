<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

use Closure;
use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedIdentifierToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;
use function Netmosfera\PHPCSSAST\StandardTokenizer\eatIdentifierToken;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function array_unshift;

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
    public function data1(){
        return cartesianProduct(
            ANY_UTF8(),
            makePiecesSample(Closure::fromCallable([$this, "getPieces"])),
            ["", "XXXX"]
        );
    }

    /** @dataProvider data1 */
    public function test1(String $prefix, Array $pieces, String $rest){
        if($pieces === []){
            $pieces = [new CheckedNameBitToken("--")];
        }elseif($pieces[0] instanceof NameBitToken){
            $pieces[0] = new CheckedNameBitToken("--" . $pieces[0]);
        }else{
            array_unshift($pieces, new CheckedNameBitToken("--"));
        }
        $traverser = getTraverser($prefix, implode("", $pieces) . $rest);
        $expected = new CheckedIdentifierToken(new CheckedNameToken($pieces));
        $eatEscape = function(Traverser $traverser): ?EscapeToken{
            return $traverser->eatStr("\\@") === NULL ? NULL :
                new CheckedEncodedCodePointEscapeToken("@");
        };
        $actual = eatIdentifierToken($traverser, "S", "N", $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data2(){
        return cartesianProduct(ANY_UTF8(), ["", "XXXX"]);
    }

    /** @dataProvider data2 */
    public function test2(String $prefix, String $rest){
        $traverser = getTraverser($prefix, "-" . $rest);
        $expected = NULL;
        $eatEscape = function(Traverser $traverser): ?EscapeToken{
            return NULL;
        };
        $actual = eatIdentifierToken($traverser, "S", "N", $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), "-" . $rest);
    }

    public function data3(){
        return cartesianProduct(
            ANY_UTF8(),
            makePiecesSample(Closure::fromCallable([$this, "getPieces"])),
            ["", "XXXX"]);
    }

    /** @dataProvider data3 */
    public function test3(String $prefix, Array $pieces, String $rest){
        if($pieces === []){
            $pieces = [new CheckedNameBitToken("-S")];
        }elseif($pieces[0] instanceof NameBitToken){
            $pieces[0] = new CheckedNameBitToken("-S" . $pieces[0]);
        }else{
            array_unshift($pieces, new CheckedNameBitToken("-S"));
        }
        $traverser = getTraverser($prefix, implode("", $pieces) . $rest);
        $expected = new CheckedIdentifierToken(new CheckedNameToken($pieces));
        $eatEscape = function(Traverser $traverser): ?EscapeToken{
            return $traverser->eatStr("\\@") === NULL ? NULL :
                new CheckedEncodedCodePointEscapeToken("@");
        };
        $actual = eatIdentifierToken($traverser, "S", "N", $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data4(){
        return cartesianProduct(
            ANY_UTF8(),
            makePiecesSample(Closure::fromCallable([$this, "getPieces"])),
            ["", "XXXX"]
        );
    }

    /** @dataProvider data4 */
    public function test4(String $prefix, Array $pieces, String $rest){
        array_unshift(
            $pieces,
            new CheckedNameBitToken("-"),
            new CheckedEncodedCodePointEscapeToken("@")
        );
        $traverser = getTraverser($prefix, implode("", $pieces) . $rest);
        $expected = new CheckedIdentifierToken(new CheckedNameToken($pieces));
        $eatEscape = function(Traverser $traverser): ?EscapeToken{
            return $traverser->eatStr("\\@") === NULL ? NULL :
                new CheckedEncodedCodePointEscapeToken("@");
        };
        $actual = eatIdentifierToken($traverser, "S", "N", $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data5(){
        return cartesianProduct(ANY_UTF8(), ["", "XXXX"]);
    }

    /** @dataProvider data5 */
    public function test5(String $prefix, String $rest){
        $traverser = getTraverser($prefix, $rest);
        $expected = NULL;
        $eatEscape = function(Traverser $traverser): ?EscapeToken{
            return NULL;
        };
        $actual = eatIdentifierToken($traverser, "S", "N", $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data6(){
        return cartesianProduct(
            ANY_UTF8(),
            makePiecesSample(Closure::fromCallable([$this, "getPieces"])),
            ["", "XXXX"]
        );
    }

    /** @dataProvider data6 */
    public function test6(String $prefix, Array $pieces, String $rest){
        if($pieces === []){
            $pieces = [new CheckedNameBitToken("S")];
        }elseif($pieces[0] instanceof NameBitToken){
            $pieces[0] = new CheckedNameBitToken("S" . $pieces[0]);
        }
        else{
            array_unshift($pieces, new CheckedNameBitToken("S"));
        }
        $traverser = getTraverser($prefix, implode("", $pieces) . $rest);
        $expected = new CheckedIdentifierToken(new CheckedNameToken($pieces));
        $eatEscape = function(Traverser $traverser): ?EscapeToken{
            return $traverser->eatStr("\\@") === NULL ? NULL :
                new CheckedEncodedCodePointEscapeToken("@");
        };
        $actual = eatIdentifierToken($traverser, "S", "N", $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data7(){
        return cartesianProduct(
            ANY_UTF8(),
            makePiecesSample(Closure::fromCallable([$this, "getPieces"])),
            ["", "XXXX"]
        );
    }

    /** @dataProvider data7 */
    public function test7(String $prefix, Array $pieces, String $rest){
        array_unshift($pieces, new CheckedEncodedCodePointEscapeToken("@"));
        $traverser = getTraverser($prefix, implode("", $pieces) . $rest);
        $expected = new CheckedIdentifierToken(new CheckedNameToken($pieces));
        $eatEscape = function(Traverser $traverser): ?EscapeToken{
            return $traverser->eatStr("\\@") === NULL ? NULL :
                new CheckedEncodedCodePointEscapeToken("@");
        };
        $actual = eatIdentifierToken($traverser, "S", "N", $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function getPieces($afterPiece){
        if(!$afterPiece instanceof NameBitToken){
            $data[] = new CheckedNameBitToken("N");
            $data[] = new CheckedNameBitToken("NN");
            $data[] = new CheckedNameBitToken("NNN");
        }
        $data[] = new CheckedEncodedCodePointEscapeToken("@");
        return $data;
    }
}
