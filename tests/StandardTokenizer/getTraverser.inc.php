<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;

function getTraverser(String $prefix, String $continuation){
    $traverser = new Traverser($prefix . $continuation, TRUE);
    assert($traverser->eatStr($prefix) === $prefix);
    return $traverser;
}
