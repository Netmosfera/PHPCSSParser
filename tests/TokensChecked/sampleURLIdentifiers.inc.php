<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked;

use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedIdentifierToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;

function sampleURLIdentifiers(){
    $pieces = [];
    $pieces[] = new CheckedNameBitToken("URL");
    $URLs[] = new CheckedIdentifierToken(new CheckedNameToken($pieces));

    $pieces = [];
    $pieces[] = new CheckedNameBitToken("url");
    $URLs[] = new CheckedIdentifierToken(new CheckedNameToken($pieces));

    $pieces = [];
    $pieces[] = new CheckedNameBitToken("UrL");
    $URLs[] = new CheckedIdentifierToken(new CheckedNameToken($pieces));

    $pieces = [];
    $pieces[] = new CheckedNameBitToken("U");
    $pieces[] = new CheckedEncodedCodePointEscapeToken("r");
    $pieces[] = new CheckedCodePointEscapeToken(dechex(ord("L")), NULL);
    $URLs[] = new CheckedIdentifierToken(new CheckedNameToken($pieces));

    return $URLs;
}
