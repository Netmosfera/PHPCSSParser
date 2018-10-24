<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\TokensChecked\Names\URLs;

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsBitToken;

class CheckedBadURLRemnantsBitToken extends BadURLRemnantsBitToken
{
    /** @inheritDoc */
    public function __construct(String $text){
        if(
            preg_match(
                '/^[' . SpecData::$instance->BAD_URL_REMNANTS_BIT_CPS_REGEX_SET . ']+$/usD',
                $text
            ) === 0
        ){
            throw new InvalidToken();
        }
        parent::__construct($text);
    }
}



