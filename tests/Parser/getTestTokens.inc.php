<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use Netmosfera\PHPCSSAST\Tokenizer\Tokenizer;
use function Netmosfera\PHPCSSAST\Tokenizer\verifyTokens;

function getTestTokens(String $CSS){
    $tokens = (new Tokenizer())->tokenize($CSS);
    verifyTokens($tokens);
    return $tokens;
}
