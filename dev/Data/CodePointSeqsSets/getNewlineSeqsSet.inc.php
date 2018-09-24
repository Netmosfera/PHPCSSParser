<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\Data\CodePointSeqsSets;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSASTDev\Data\TextSet;

function getNewlineSeqsSet(): TextSet{
    return new TextSet(["\r\n", "\r", "\n", "\f"]);
}
