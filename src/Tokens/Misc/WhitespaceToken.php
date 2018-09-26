<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokens\Misc;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\Token;
use function Netmosfera\PHPCSSAST\match;
use function preg_replace;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * A {@see WhitespaceToken} is a sequence of one or more whitespace code points.
 *
 * The whitespace code points are defined in {@see SpecData::WHITESPACES_SET}.
 */
class WhitespaceToken implements Token
{

    /**
     * @var         String
     * `String`
     */
    private $whitespaces;

    /**
     * @var         String|NULL
     * `String|NULL`
     */
    private $normalizedObject;

    /**
     * @param       String                                  $whitespaces
     * `String`
     * The whitespace sequence.
     */
    function __construct(String $whitespaces){
        assert(preg_match('/^[ \\t\\r\\n\\f]+$/usD', $whitespaces) === 1);
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
    function normalize(): WhitespaceToken{
        if($this->normalizedObject === NULL){
            $this->normalizedObject = new WhitespaceToken(
                preg_replace(
                    "/" . SpecData::WHITESPACES_SEQS_SET . "/usD",
                    SpecData::WHITESPACE,
                    $this->whitespaces
                )
            );
        }
        return $this->normalizedObject;
    }
}
