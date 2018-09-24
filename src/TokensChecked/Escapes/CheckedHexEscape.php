<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\TokensChecked\Escapes;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\Escapes\HexEscape;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class CheckedHexEscape extends HexEscape
{
    function __construct(String $hexDigits, ?WhitespaceToken $terminator){
        if(
            preg_match('/^[' . SpecData::HEX_DIGITS_SET . ')]{1,6}$/usD', $hexDigits) === 0 ||
            ($terminator !== NULL && mb_strlen($terminator->getValue()) != 1)
        ){
            throw new InvalidToken();
        }
        parent::__construct($hexDigits, $terminator);
    }
}
