<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\TokensChecked;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokenizer\FastTokenizer;
use Netmosfera\PHPCSSAST\Tokens\Tokens;

class CheckedTokens extends Tokens
{
    /** @inheritDoc */
    public function __construct(array $tokens){
        parent::__construct($tokens);

        $controlTokens = (new FastTokenizer())->tokenize(implode("", $tokens));

        if(match($tokens, $controlTokens->tokens()) === FALSE){
            // @TODO maybe explain why the tokens are invalid?
            throw new InvalidToken();
        }
    }
}
