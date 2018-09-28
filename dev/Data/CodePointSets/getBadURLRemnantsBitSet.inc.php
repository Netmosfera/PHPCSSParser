<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\Data\CodePointSets;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use function Netmosfera\PHPCSSASTDev\Data\cp;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getBadURLRemnantsBitSet(): CompressedCodePointSet{
    $set = new CompressedCodePointSet();
    $set->selectAll();
    $set->remove(cp(")")); // represents the end of the badurlremnants token
    $set->remove(cp("\\")); // escapes are collected otherwise in a badurlremnantstoken
    return $set;
}
