<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\TokensChecked\Escapes;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ContinuationEscape;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class CheckedContinuationEscape extends ContinuationEscape
{
    function __construct(String $codePoint){
        if(preg_match('/^(?:' . SpecData::NEWLINES_SEQS_SET . ')$/usD', $codePoint) === 0){
            throw new InvalidToken();
        }
        parent::__construct($codePoint);
    }
}
