<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Parser\Components;

use Netmosfera\PHPCSSAST\Tokens\Token;

class TokenStream
{
    /**
     * @var         Token[]
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
