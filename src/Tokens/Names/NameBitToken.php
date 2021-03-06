<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Names;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\EvaluableToken;
use Netmosfera\PHPCSSAST\SpecData;

/**
 * @TODOC
 */
class NameBitToken implements EvaluableToken
{
    /**
     * @var         String
     * `String`
     */
    private $_text;

    /**
     * @param       String $text
     * `String`
     * @TODOC
     */
    public function __construct(String $text){
        $this->_text = $text;
    }

    /** @inheritDoc */
    public function __toString(): String{
        return $this->_text;
    }

    /** @inheritDoc */
    public function newlineCount(): Int{
        return 0;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->_text, $other->_text);
    }

    /** @inheritDoc */
    public function intendedValue(): String{ // @memo
        return str_replace(
            "\0",
            SpecData::REPLACEMENT_CHARACTER,
            $this->_text
        );
    }
}
