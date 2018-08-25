<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Traverser;
use Netmosfera\PHPCSSAST\Tokens\IdentifierToken;
use Netmosfera\PHPCSSAST\Tokens\SubTokens\PlainEscape;
use Netmosfera\PHPCSSAST\Tokens\SubTokens\ActualEscape;
use function Netmosfera\PHPCSSAST\Tokenizer\eatIdentifierToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class eatIdentifierTokenTest extends TestCase
{
    // @TODO improve these tests
    function test(){
        $prefix = "SKIPME";
        $rest = "@ <- end";

        $pieces[] = "plain-string-\u{2764}-with-non-ASCII__12345";
        $pieces[] = new PlainEscape("x");
        $pieces[] = new ActualEscape("ffa1", "\n");
        $pieces[] = new PlainEscape("u");
        $pieces[] = new ActualEscape("CcCcCc", "\f");
        $pieces[] = new ActualEscape("23c4aD", "\r\n");
        $pieces[] = "plain-string";

        $name = "";
        foreach($pieces as $piece){
            if(is_string($piece)){
                $name .= $piece;
            }elseif($piece instanceof PlainEscape){
                $name .= "\\" . $piece->codePoint;
            }elseif($piece instanceof ActualEscape){
                $name .= "\\" . $piece->hexDigits . $piece->whitespace;
            }
        }

        $expected = new IdentifierToken($pieces);

        $t = new Traverser($prefix . $name . $rest, TRUE);
        $t->eatStr($prefix);
        assertMatch(eatIdentifierToken($t), $expected);
        assertMatch($t->eatAll(), $rest);
    }
}
