<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Error;
use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Traverser;
use Netmosfera\PHPCSSAST\Tokens\SubTokens\PlainEscape;
use Netmosfera\PHPCSSAST\Tokens\SubTokens\ActualEscape;
use Netmosfera\PHPCSSAST\Tokens\SubTokens\BadURLRemnants;
use function Netmosfera\PHPCSSAST\Tokenizer\eatBadURLRemnants;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class eatBadURLRemnantsTest extends TestCase
{
    // @TODO improve tests
    function ddsdfds(){
        return cartesianProduct(ANY_UTF8());
    }

    /** @dataProvider ddsdfds */
    function test(String $prefix){
        $t = new Traverser($prefix . "");
        $t->eatStr($prefix);
        $actual = eatBadURLRemnants($t);
        $expected = new BadURLRemnants([], TRUE);
        assertMatch($actual, $expected);
    }

    function xxxx(){
        return cartesianProduct(ANY_UTF8());
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

    /** @dataProvider xxxx */
    function testdsdfs(String $prefix){
        $pieces[] = "foo bar baz \" ' ( baz";
        $pieces[] = new PlainEscape("\n");
        $pieces[] = "foo bar baz \" ' ( baz";
        $pieces[] = new PlainEscape("\\");
        $pieces[] = new PlainEscape("x");
        $pieces[] = "foo bar baz \" ' ( baz";
        $pieces[] = new ActualEscape("fFaAcC", "\r\n");

        $rest = "sdfijsd";
        $t = new Traverser($prefix . $this->URLify($pieces) . ")" . $rest, TRUE);
        $t->eatStr($prefix);
        $actual = eatBadURLRemnants($t);
        $expected = new BadURLRemnants($pieces, FALSE);
        assertMatch($actual, $expected);
    }
}
