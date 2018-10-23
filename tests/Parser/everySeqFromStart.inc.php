<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use function array_slice;

function everySeqFromStart(array $sequence){
    $sequences = [];
    for($length = 0; $length <= count($sequence); $length++){
        $sequences[] = array_slice($sequence, 0, $length);
    }
    return $sequences;
}
