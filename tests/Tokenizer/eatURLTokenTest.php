<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Traverser;
use Netmosfera\PHPCSSAST\Tokens\URLToken;
use Netmosfera\PHPCSSAST\Tokens\SubTokens\PlainEscape;
use Netmosfera\PHPCSSAST\Tokens\SubTokens\ActualEscape;
use function Netmosfera\PHPCSSAST\Tokenizer\eatURLToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | start_whitespace? | EOF
 * #2 | start_whitespace? | ")" . $anyRest
 * #3 | start_whitespace? | $endWhitespace . EOF
 * #4 | start_whitespace? | $endWhitespace . ")" . $anyRest
 * #5 | start_whitespace? | $endWhitespace . "invalid_remnants"
  */
class eatURLTokenTest extends TestCase
{
    function stringifyPieces(Array $pieces){
        $URL = "";
        foreach($pieces as $piece){
            if(is_string($piece)){
                $URL .= $piece;
            }elseif($piece instanceof PlainEscape){
                $URL .= "\\" . $piece->codePoint;
            }elseif($piece instanceof ActualEscape){
                $URL .= "\\" . $piece->hexDigits . $piece->whitespace;
            }
        }
        return $URL;
    }

    function eatRemnants(Traverser $t){
        assertMatch($t->eatStr("invalid_remnants"), "invalid_remnants");
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // @TODO improve these tests
    function test(){
        $prefix = "SKIPME";
        $rest = "@ <- end";

        $pieces[] = "://www.example.org/foo/";
        $URL = $this->stringifyPieces($pieces);

        $t = new Traverser($prefix . $URL . ")" . $rest, TRUE);
        $t->eatStr($prefix);

        $expected = new URLToken(NULL, $pieces, FALSE, NULL);

        assertMatch(eatURLToken($t, function($t){}), $expected);

        assertMatch($t->eatAll(), $rest);
    }
}
