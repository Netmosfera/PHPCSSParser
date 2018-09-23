<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getTraverser(String $prefix, String $continuation){
    $traverser = new Traverser($prefix . $continuation);
    assert($traverser->eatStr($prefix) === $prefix);
    return $traverser;
}
