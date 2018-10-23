<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Token;

class PreservedToken implements ComponentValueNode
{
    private $_token;

    public function __construct(Token $token){
        $this->_token = $token;
    }

    /** @inheritDoc */
    function __toString(): String{
        return (String)$this->_token;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->_token, $this->_token) &&
            TRUE;
    }

    public function token(): Token{
        return $this->_token;
    }
}
