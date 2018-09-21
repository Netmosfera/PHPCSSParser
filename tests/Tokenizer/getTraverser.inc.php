<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Traverser;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getTraverser(String $prefix, String $continuation){
    $traverser = new Traverser($prefix . $continuation);
    assert($traverser->eatStr($prefix) === $prefix);
    return $traverser;
}
