<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Misc;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\EvaluableToken;
use function Netmosfera\PHPCSSAST\match;
use function preg_replace;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * A {@see WhitespaceToken} is a sequence of one or more whitespace code points.
 *
 * The whitespace code points are defined in {@see SpecData::WHITESPACES_SET}.
 */
class WhitespaceToken implements EvaluableToken
{

    /**
     * @var         String                                                                  `String`
     */
    private $whitespaces;

    /**
     * @var         String|NULL                                                             `String|NULL`
     */
    private $value;

    /**
     * @param       String                                  $whitespaces                    `String`
     * The whitespace sequence.
     */
    function __construct(String $whitespaces){
        $this->whitespaces = $whitespaces;
    }

    /** @inheritDoc */
    function __toString(): String{
        return $this->whitespaces;
    }

    /** @inheritDoc */
    function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->whitespaces, $this->whitespaces);
    }

    /** @inheritDoc */
    function getValue(): String{
        if($this->value === NULL){
            $this->value = preg_replace(
                "/" . SpecData::WHITESPACES_SEQS_SET . "/usD",
                "\n",
                $this->whitespaces
            );
        }
        return $this->value;
    }
}
