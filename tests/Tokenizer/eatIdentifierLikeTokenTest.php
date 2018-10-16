<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

use function dechex;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSAST\Tokenizer\eatIdentifierLikeToken;
use function Netmosfera\PHPCSSASTTests\Tokenizer\Fakes\eatURLTokenFunction;
use function Netmosfera\PHPCSSASTTests\Tokenizer\Fakes\eatIdentifierTokenFunction;
use function Netmosfera\PHPCSSASTTests\Tokenizer\Fakes\eatURLTokenFailingFunction;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedURLBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedIdentifierToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedURLToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedFunctionToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use PHPUnit\Framework\TestCase;
use IntlChar;


/**
 * Tests in this file:
 *
 * #1 | no identifier returns NULL
 * #2 | IdentifierToken if not followed by (
 * #3 | URLToken if url( not followed by string delimiter
 * #4 | FunctionToken if url( followed by string delimiter
 * #5 | FunctionToken otherwise
 */
class eatIdentifierLikeTokenTest extends TestCase
{
    private function URLIdentifiers(){
        $a = function(String $bit){
            return new CheckedNameBitToken($bit);
        };

        $b = function(String $cp){
            return new CheckedEncodedCodePointEscapeToken($cp);
        };

        $c = function(String $cp){
            $hexDigits = dechex(IntlChar::ord($cp));
            return new CheckedCodePointEscapeToken($hexDigits, NULL);
        };

        $names = [];
        $names[] = new CheckedNameToken([$a("url")]);
        $names[] = new CheckedNameToken([$a("u"), $b("r"), $c("l")]);
        $names[] = new CheckedNameToken([$c("u"), $a("r"), $b("l")]);
        $names[] = new CheckedNameToken([$b("u"), $c("r"), $a("l")]);

        $urls = [];
        foreach($names as $name){
            $urls[] = new CheckedIdentifierToken($name);
        }
        return $urls;
    }

    //------------------------------------------------------------------------------------

    public function data1(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8("@ not name-start cp"));
    }

    /** @dataProvider data1 */
    public function test1(String $prefix, String $rest){
        $identifierLike = NULL;

        $traverser = getTraverser($prefix, $rest);
        $eatIdentifier = eatIdentifierTokenFunction(NULL);
        $eatURL = eatURLTokenFailingFunction();
        $actualIdentifierLike = eatIdentifierLikeToken($traverser, $eatIdentifier, $eatURL);

        assertMatch($actualIdentifierLike, $identifierLike);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data2(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8("@ not name cp"));
    }

    /** @dataProvider data2 */
    public function test2(String $prefix, String $rest){
        $nameBit = new CheckedNameBitToken("identifier_name");
        $name = new CheckedNameToken([$nameBit]);
        $identifierLike = new CheckedIdentifierToken($name);

        $traverser = getTraverser($prefix, $identifierLike . $rest);
        $eatIdentifier = eatIdentifierTokenFunction($identifierLike);
        $eatURL = eatURLTokenFailingFunction();
        $actualIdentifierLike = eatIdentifierLikeToken($traverser, $eatIdentifier, $eatURL);

        assertMatch($actualIdentifierLike, $identifierLike);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data3(){
        return cartesianProduct(
            ANY_UTF8(),
            $this->URLIdentifiers(),
            ANY_UTF8("@ not name code point")
        );
    }

    /** @dataProvider data3 */
    public function test3(String $prefix, IdentifierToken $identifier, String $rest){
        $URLBit = new CheckedURLBitToken("works");
        $identifierLike = new CheckedURLToken($identifier, NULL, [$URLBit], NULL, FALSE);

        $traverser = getTraverser($prefix, $identifierLike . $rest);
        $eatIdentifier = eatIdentifierTokenFunction($identifier);
        $eatURL = eatURLTokenFunction($identifierLike);
        $actualIdentifierLike = eatIdentifierLikeToken($traverser, $eatIdentifier, $eatURL);

        assertMatch($actualIdentifierLike, $identifierLike);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data4(){
        return cartesianProduct(
            ANY_UTF8(),
            $this->URLIdentifiers(),
            ANY_UTF8("@ not name code point")
        );
    }

    /** @dataProvider data4 */
    public function test4(String $prefix, IdentifierToken $URLIdentifier, String $rest){
        $identifierLike = new CheckedFunctionToken($URLIdentifier);

        $traverser = getTraverser($prefix, $identifierLike . $rest);
        $eatIdentifier = eatIdentifierTokenFunction($URLIdentifier);
        $eatURL = eatURLTokenFunction(NULL);
        $actualIdentifierLike = eatIdentifierLikeToken($traverser, $eatIdentifier, $eatURL);

        assertMatch($actualIdentifierLike, $identifierLike);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data5(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8("@not name code point"));
    }

    /** @dataProvider data5 */
    public function test5(String $prefix, String $rest){
        $nameBit = new CheckedNameBitToken("func_name");
        $name = new CheckedNameToken([$nameBit]);
        $identifier = new CheckedIdentifierToken($name);
        $identifierLike = new CheckedFunctionToken($identifier);

        $traverser = getTraverser($prefix, $identifierLike . $rest);
        $eatIdentifier = eatIdentifierTokenFunction($identifier);
        $eatURLToken = eatURLTokenFailingFunction();
        $actualIdentifierLike = eatIdentifierLikeToken($traverser, $eatIdentifier, $eatURLToken);

        assertMatch($actualIdentifierLike, $identifierLike);
        assertMatch($traverser->eatAll(), $rest);
    }
}
