<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser;

use Netmosfera\PHPCSSAST\Tokens\Token;

class TokenStream
{
    /**
     * @var         array|Token[]
     * `Array<Int, Token>`
     * @TODOC
     */
    public $tokens;

    /**
     * @var         Int
     * `Int`
     * @TODOC
     */
    public $index;

    public function __construct(array $tokens){
        $this->tokens = $tokens;
        $this->index = 0;
    }
}
