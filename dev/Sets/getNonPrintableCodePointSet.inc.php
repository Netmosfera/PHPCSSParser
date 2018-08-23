<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\Sets;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use Netmosfera\PHPCSSASTDev\ContiguousCodePointsSet;
use function Netmosfera\PHPCSSASTDev\cp;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getNonPrintableCodePointSet(){
    $set = new CompressedCodePointSet();
    $set->addAll(new ContiguousCodePointsSet(cp("\u{0}"), cp("\u{8}")));
    $set->add(cp("\u{B}"));
    $set->addAll(new ContiguousCodePointsSet(cp("\u{E}"), cp("\u{1F}")));
    $set->add(cp("\u{7F}"));
    return $set;
}
