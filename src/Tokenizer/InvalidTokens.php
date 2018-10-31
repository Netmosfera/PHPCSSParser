<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Exception;
use Netmosfera\PHPCSSAST\Tokens\RootToken;

class InvalidTokens extends Exception
{
    private $object;

    public function __construct(
        RootToken $object
    ){
        parent::__construct("The token is invalid");

        $this->object = $object;
    }
}
