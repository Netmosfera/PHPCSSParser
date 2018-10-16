<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

function getToken(String $css){
    return getTokens($css)[0];
}
