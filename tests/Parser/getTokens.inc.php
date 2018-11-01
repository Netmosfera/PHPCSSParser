<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use Netmosfera\PHPCSSAST\Tokenizer\Tokenizer;
use function Netmosfera\PHPCSSAST\Tokenizer\verifyTokens;

function getTokens(String $css){
    $tokens = (new Tokenizer())->tokenize($css);
    verifyTokens($tokens);
    return $tokens;
}
