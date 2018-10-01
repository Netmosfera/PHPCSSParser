<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\TokensChecked\Escapes;

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EncodedCodePointEscapeToken;

class CheckedEncodedCodePointEscapeToken extends EncodedCodePointEscapeToken
{
    public function __construct(String $codePoint){
        if(
            preg_match(
                '/^[' . SpecData::ENCODED_ESCAPE_SET . ']$/usD',
                $codePoint
            ) === 0
        ){
            throw new InvalidToken();
        }
        parent::__construct($codePoint);
    }
}
