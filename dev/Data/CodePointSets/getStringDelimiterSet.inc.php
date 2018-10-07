<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\Data\CodePointSets;

use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use function Netmosfera\PHPCSSASTDev\Data\cp;

function getStringDelimiterSet(): CompressedCodePointSet{
    $set = new CompressedCodePointSet();
    $set->add(cp("\""));
    $set->add(cp("'"));
    return $set;
}
