<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\TokensChecked\Operators;

use function mb_strlen;
use Netmosfera\PHPCSSAST\Tokens\Operators\DelimiterToken;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;

class CheckedDelimiterToken extends DelimiterToken
{
    /** @inheritDoc */
    public function __construct(String $delimiter){

        // @TBD make this stricter? some characters can never appear as this

        if(mb_strlen($delimiter) !== 1){
            throw new InvalidToken();
        }

        parent::__construct($delimiter);
    }
}
