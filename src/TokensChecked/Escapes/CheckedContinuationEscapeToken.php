<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\TokensChecked\Escapes;

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ContinuationEscapeToken;

class CheckedContinuationEscapeToken extends ContinuationEscapeToken
{
    public function __construct(String $codePoint){
        if(
            preg_match(
                '/^(?:' . SpecData::NEWLINES_REGEX_SEQS . ')$/usD',
                $codePoint
            ) === 0
        ){
            throw new InvalidToken();
        }
        parent::__construct($codePoint);
    }
}
