<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use function Netmosfera\PHPCSSAST\Tokenizer\eatIdentifierToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\ActualEscape;
use Netmosfera\PHPCSSAST\Tokens\Strings\PlainEscape;
use Netmosfera\PHPCSSAST\Tokens\IdentifierToken;
use Netmosfera\PHPCSSAST\Traverser;
use PHPUnit\Framework\TestCase;

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
        self::assertTrue(match(eatIdentifierToken($t), $expected));
        self::assertTrue(match($t->eatAll(), $rest));
    }
}
