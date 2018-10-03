<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\TokensChecked\Strings;

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringBitToken;

class CheckedStringBitToken extends StringBitToken
{
    public function __construct(String $text){
        if(
            preg_match(
                '/^[' . SpecData::STRING_BIT_CPS_REGEX_SET . ']+$/usD',
                $text
            ) === 0
        ){
            throw new InvalidToken();
        }
        parent::__construct($text);
    }
}
