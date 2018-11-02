<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use function Netmosfera\PHPCSSAST\Parser\Components\tokensToComponents;

function getTestComponents(String $CSS){
    return tokensToComponents(getTestTokens($CSS));
}
