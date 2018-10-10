<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\FastTokenizer;

use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;

function verifyUnicodeCovered(array $sets){
    $allCodePoints = new CompressedCodePointSet();
    $allCodePoints->selectAll();
    foreach($sets as $set){
        $allCodePoints->removeAll($set);
    }
    assert($allCodePoints->count() === 0);
}
