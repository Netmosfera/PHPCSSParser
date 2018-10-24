<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\TokensChecked\Misc;

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;

class CheckedWhitespaceToken extends WhitespaceToken
{
    /** @inheritDoc */
    public function __construct(String $text){
        if(preg_match('/^[' . SpecData::$instance->WHITESPACES_REGEX_SET . ']+$/usD', $text) === 0){
            throw new InvalidToken();
        }
        parent::__construct($text);
    }
}
