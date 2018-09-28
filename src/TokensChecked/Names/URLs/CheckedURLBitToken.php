<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\TokensChecked\Names\URLs;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\URLBitToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class CheckedURLBitToken extends URLBitToken
{
    function __construct(String $text){
        if(preg_match('/^[' . SpecData::URLTOKEN_BIT_CP_SET . ']+$/usD', $text) === 0){
            throw new InvalidToken();
        }
        parent::__construct($text);
    }
}
