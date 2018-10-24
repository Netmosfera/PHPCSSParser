<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\TokensChecked\Names\URLs;

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\URLBitToken;

class CheckedURLBitToken extends URLBitToken
{
    /** @inheritDoc */
    public function __construct(String $text){
        if(
            preg_match(
                '/^[' . SpecData::$instance->URL_TOKEN_BIT_CPS_REGEX_SET . ']+$/usD',
                $text
            ) === 0
        ){
            throw new InvalidToken();
        }
        parent::__construct($text);
    }
}
