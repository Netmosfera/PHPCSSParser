<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

use Closure;
use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedIdentifierToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;
use function Netmosfera\PHPCSSASTTests\StandardTokenizer\Fakes\eatValidEscapeTokenFunction;
use function Netmosfera\PHPCSSAST\StandardTokenizer\eatIdentifierToken;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 covers all working cases except #2
 * #2 covers "-" followed by valid escape
 */
class eatIdentifierTokenTest extends TestCase
{
    private function piecesAfter($afterPiece){
        if($afterPiece === NULL){
            // IdentifierToken must not start with digit or - followed by a digit
            $data[] = new CheckedNameBitToken("S");
            $data[] = new CheckedNameBitToken("SN");
            $data[] = new CheckedNameBitToken("SNN");
            $data[] = new CheckedNameBitToken("-S");
            $data[] = new CheckedNameBitToken("-SN");
            $data[] = new CheckedNameBitToken("-SNN");
            $data[] = new CheckedNameBitToken("--");
            $data[] = new CheckedNameBitToken("--N");
            $data[] = new CheckedNameBitToken("--NN");
            $data[] = new CheckedNameBitToken("--NNN");
            $data[] = new CheckedEncodedCodePointEscapeToken("@");
            $data[] = new CheckedCodePointEscapeToken("2764", NULL);
        }elseif($afterPiece instanceof CheckedNameBitToken){
            // CheckedNameBitToken can *not* appear after another one, only escapes can
            $data[] = new CheckedEncodedCodePointEscapeToken("@");
            $data[] = new CheckedCodePointEscapeToken("2764", NULL);
        }else{
            assert($afterPiece instanceof EscapeToken);
            // After a ValidEscapeToken can appear anything
            $data[] = new CheckedEncodedCodePointEscapeToken("@");
            $data[] = new CheckedCodePointEscapeToken("2764", NULL);
            $data[] = new CheckedNameBitToken("N");
            $data[] = new CheckedNameBitToken("NN");
            $data[] = new CheckedNameBitToken("NNN");
        }
        return $data;
    }

    //------------------------------------------------------------------------------------

    public function data1(){
        return cartesianProduct(
            ANY_UTF8(),
            makePiecesSample(Closure::fromCallable([$this, "piecesAfter"]), FALSE),
            ANY_UTF8("not starting with N (a name code point)")
        );
    }

    /** @dataProvider data1 */
    public function test1(String $prefix, Array $pieces, String $rest){
        $escape1 = new CheckedCodePointEscapeToken("2764", NULL);
        $escape2 = new CheckedEncodedCodePointEscapeToken("@");
        $name = new CheckedNameToken($pieces);
        $identifier = new CheckedIdentifierToken($name);

        $traverser = getTraverser($prefix, $identifier . $rest);
        $eatEscape = eatValidEscapeTokenFunction([$escape1, $escape2]);
        $actualIdentifier = eatIdentifierToken($traverser, "S", "N", $eatEscape);

        assertMatch($actualIdentifier, $identifier);
        assertMatch($traverser->eatAll(), $rest);
    }

    // @TODO test 2 and tests that return NULL
}
