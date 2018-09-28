<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\TokensChecked\Names\URLs;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsBitToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class CheckedBadURLRemnantsBitToken extends BadURLRemnantsBitToken
{
    function __construct(String $text){
        if(preg_match('/^[' . SpecData::BAD_URL_REMNANTS_BIT_SET . ']+$/usD', $text) === 0){
            throw new InvalidToken();
        }
        parent::__construct($text);
    }
}



