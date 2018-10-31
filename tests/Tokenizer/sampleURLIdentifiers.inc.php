<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

use Netmosfera\PHPCSSAST\Tokens\Escapes\CodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EncodedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;

function sampleURLIdentifiers(){
    $pieces = [];
    $pieces[] = new NameBitToken("URL");
    $URLs[] = new IdentifierToken(new NameToken($pieces));

    $pieces = [];
    $pieces[] = new NameBitToken("url");
    $URLs[] = new IdentifierToken(new NameToken($pieces));

    $pieces = [];
    $pieces[] = new NameBitToken("UrL");
    $URLs[] = new IdentifierToken(new NameToken($pieces));

    $pieces = [];
    $pieces[] = new NameBitToken("U");
    $pieces[] = new EncodedCodePointEscapeToken("r");
    $pieces[] = new CodePointEscapeToken(dechex(ord("L")), NULL);
    $URLs[] = new IdentifierToken(new NameToken($pieces));

    return $URLs;
}
