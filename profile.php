<?php

use Netmosfera\PHPCSSAST\Tokenizer\Tokenizer;
use function Netmosfera\PHPCSSAST\Tokenizer\verifyTokens;
use Netmosfera\PHPCSSAST\Tokens\Escapes\CodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EncodedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EOFEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringBitToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringToken;
use function Netmosfera\PHPCSSASTTests\Parser\getTokens;

require(__DIR__ . "/vendor/autoload.php");

$tokens = getTokens("body { color: red; } ")->tokens();

$name = new NameToken([new CodePointEscapeToken("ffaa", NULL)]);
$tokens[] = new IdentifierToken($name);


$tokens[] = new StringToken(
    "'",
    [
        new StringBitToken("hello"),
        new EOFEscapeToken(),
        new StringBitToken("bar"),
        new EncodedCodePointEscapeToken("x"),
    ],
    TRUE
);


verifyTokens($tokens);


exit();






$file = __DIR__ . "/tmp/test.css";
$css = file_get_contents(__DIR__ . "/tmp/test.css");
$tokenizer = new Tokenizer();

$st = microtime(TRUE);
$tokens = $tokenizer->tokenize($css)->tokens();
echo number_format(microtime(TRUE) - $st, 10) . "\n";

$rebuild = implode("", $tokens);
file_put_contents(__DIR__ . "/tmp/test_rebuilt.css", $rebuild);

$lineCount = 1;
foreach($tokens as $token){
    $lineCount += $token->newlineCount();
    if($token->isParseError()){
        echo "parse error in " . get_class($token);
        echo " at " . $file . ":" . $lineCount . "\n";
    }
}
