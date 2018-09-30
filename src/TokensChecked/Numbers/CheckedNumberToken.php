<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\TokensChecked\Numbers;

use Netmosfera\PHPCSSAST\Tokens\Numbers\NumberToken;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;

class CheckedNumberToken extends NumberToken
{
    public function __construct(
        String $sign,
        String $wholes,
        String $decimals,
        String $ELetter,
        String $ESign,
        String $EExponent
    ){
        if(
            ($wholes === "" && $decimals === "") ||
            ($ELetter !== "" && $EExponent === "") ||
            ($EExponent !== "" && $ELetter === "") ||
            ($ESign !== "" && $EExponent === "") ||

            ($sign !== "" && $sign !== "+" && $sign !== "-") ||
            ($ESign !== "" && $ESign !== "+" && $ESign !== "-") ||
            (preg_match("/^(|[0-9]+)$/usD", $wholes) === 0) ||
            (preg_match("/^(|[0-9]+)$/usD", $decimals) === 0) ||
            (preg_match("/^(|[0-9]+)$/usD", $EExponent) === 0) ||
            FALSE
        ){
            throw new InvalidToken();
        }

        parent::__construct(
            $sign,
            $wholes,
            $decimals,
            $ELetter,
            $ESign,
            $EExponent
        );
    }
}
