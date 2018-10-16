<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\Examples;

function ANY_CSS(String $ensureNotStartingWithPrefixByAddingThisPrefix = ""): array{
    return [
        "",
        $ensureNotStartingWithPrefixByAddingThisPrefix .
        "body{background-color:#BADA55;}"
    ];
}
