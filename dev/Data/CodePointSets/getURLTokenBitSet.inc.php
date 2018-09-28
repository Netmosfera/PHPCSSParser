<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\Data\CodePointSets;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use function Netmosfera\PHPCSSASTDev\Data\cp;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getURLTokenBitSet(): CompressedCodePointSet{
    $set = new CompressedCodePointSet();

    $set->selectAll();

    // disallowed by spec - newline causes the URL token to be truncated
    $set->removeAll(getNewlinesSet());

    // disallowed by spec
    $set->removeAll(getNonPrintablesSet());

    // re-add back null because that counts as the unicode replacement character, which is allowed in the spec
    $set->add(cp("\0"));

    // disallowed by spec. because if found at offset 0, it would represent a functiontoken
    $set->removeAll(getStringDelimiterSet());

    // disallowed by spec, not sure the reason
    $set->remove(cp("("));

    // disallowed because represents the url( ending cp
    $set->remove(cp(")"));

    // disallowed because \ appears as escape sequences objects
    $set->remove(cp("\\"));

    return $set;
}
