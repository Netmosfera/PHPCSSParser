<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\TokensChecked\Misc;

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;

class CheckedWhitespaceToken extends WhitespaceToken
{
    public function __construct(String $whitespaces){
        if(
            preg_match(
                '/^[' . SpecData::WHITESPACES_SET . ']+$/usD',
                $whitespaces
            ) === 0
        ){
            throw new InvalidToken();
        }
        parent::__construct($whitespaces);
    }
}
