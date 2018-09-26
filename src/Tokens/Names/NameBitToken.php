<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Names;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\EvaluableToken;
use Netmosfera\PHPCSSAST\SpecData;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * A {@see NameBitToken} is sequence of {@see SpecData::NAME_ITEMS_SET} code points.
 */
class NameBitToken implements EvaluableToken
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
     * The sequence of {@see SpecData::NAME_ITEMS_SET} code points.
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

    /** @inheritDoc */
    function getValue(): String{
        if($this->value === NULL){
            $this->value = str_replace("\0", SpecData::REPLACEMENT_CHARACTER, $this->text);
        }
        return $this->value;
    }
}
