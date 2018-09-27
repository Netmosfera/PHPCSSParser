<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Strings;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\EvaluableToken;
use Netmosfera\PHPCSSAST\SpecData;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * A {@see StringBitToken} is sequence of {@see SpecData:: @TODO } code points.
 */
class StringBitToken implements EvaluableToken
{
    /**
     * @var         String
     * `String`
     */
    private $text;

    /**
     * @var         String|NULL
     * `String|NULL`
     */
    private $value;

    /**
     * @param       String                                  $text
     * `String`
     * @TODOC
     */
    function __construct(String $text){
        $this->text = $text;
    }

    /** @inheritDoc */
    function __toString(): String{
        return $this->text;
    }

    /** @inheritDoc */
    function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->text, $this->text);
    }

    /**
     * @TODOC
     *
     * @returns String
     * `String`
     * @TODOC
     */
    function getText(): String{
        return $this->text;
    }

    /**
     * @TODOC
     *
     * @returns String
     * `String`
     * @TODOC
     */
    function getValue(): String{
        if($this->value === NULL){
            $this->value = str_replace("\0", SpecData::REPLACEMENT_CHARACTER, $this->text);
        }
        return $this->value;
    }
}
