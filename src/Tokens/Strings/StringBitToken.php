<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Strings;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\EvaluableToken;
use Netmosfera\PHPCSSAST\SpecData;

/**
 * A {@see StringBitToken} is sequence of {@see SpecData::STRING_BIT_CP_SET} code points.
 */
class StringBitToken implements EvaluableToken
{
    /**
     * @var         String
     * `String`
     */
    private $_text;

    /**
     * @var         String|NULL
     * `String|NULL`
     */
    private $_intendedValue;

    /**
     * @param       String                                  $text
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
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->_text, $this->_text);
    }

    /**
     * @TODOC
     *
     * @return      String
     * `String`
     * @TODOC
     */
    public function intendedValue(): String{
        if($this->_intendedValue === NULL){
            $this->_intendedValue = str_replace(
                "\0",
                SpecData::REPLACEMENT_CHARACTER,
                $this->_text
            );
        }
        return $this->_intendedValue;
    }
}
