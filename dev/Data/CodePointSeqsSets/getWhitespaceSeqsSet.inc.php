<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\Data\CodePointSeqsSets;

use Netmosfera\PHPCSSASTDev\Data\TextSet;

function getWhitespaceSeqsSet(): TextSet{
    return new TextSet(["\r\n", "\r", "\n", "\f", "\t", " "]);
}
