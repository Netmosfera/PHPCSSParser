<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\Examples;

function ANY_UTF8(String $ensureNotStartingWithPrefixByAddingThisPrefix = ""){
    return ["", $ensureNotStartingWithPrefixByAddingThisPrefix . "sample \u{2764} string"];
}
