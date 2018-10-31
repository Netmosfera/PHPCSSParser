<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

use Netmosfera\PHPCSSAST\Tokenizer\InvalidTokens;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EOFEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringBitToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringToken;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSAST\Tokenizer\verifyTokens;

/**
 * Tests in this file:
 */
class verifyTokensTest extends TestCase
{
    public function test1(){
        $this->expectException(InvalidTokens::CLASS);
        $token = new StringToken("'", [
            new EOFEscapeToken(),
            new StringBitToken("hello"),
        ], TRUE);
        verifyTokens([$token]);
    }
}
