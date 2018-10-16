<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

use Netmosfera\PHPCSSAST\Tokenizer\Traverser;

function getTraverser(String $prefix, String $continuation){
    $traverser = new Traverser($prefix . $continuation, TRUE);
    assert($traverser->eatString($prefix) === $prefix);
    return $traverser;
}
