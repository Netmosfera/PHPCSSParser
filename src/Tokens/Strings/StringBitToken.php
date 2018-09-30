<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Strings;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\EvaluableToken;
use Netmosfera\PHPCSSAST\SpecData;

/**
 * A {@see StringBitToken} is sequence of {@see SpecData::STRING_BIT_CP_SET}
 * code points.
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
    public function __construct(String $text){
        $this->text = $text;
    }

    /** @inheritDoc */
    public function __toString(): String{
        return $this->text;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->text, $this->text);
    }

    /**
     * @TODOC
     *
     * @return      String
     * `String`
     * @TODOC
     */
    public function getText(): String{
        return $this->text;
    }

    /**
     * @TODOC
     *
     * @return      String
     * `String`
     * @TODOC
     */
    public function getValue(): String{
        if($this->value === NULL){
            $this->value = str_replace(
                "\0",
                SpecData::REPLACEMENT_CHARACTER,
                $this->text
            );
        }
        return $this->value;
    }
}
