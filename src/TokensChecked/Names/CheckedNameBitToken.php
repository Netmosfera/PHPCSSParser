<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\TokensChecked\Names;

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;

class CheckedNameBitToken extends NameBitToken
{
    /** @inheritDoc */
    public function __construct(String $text){
        if(
            preg_match(
                '/^[' . SpecData::NAME_COMPONENTS_REGEX_SET . ']+$/usD',
                $text
            ) === 0
        ){
            throw new InvalidToken();
        }
        parent::__construct($text);
    }
}
