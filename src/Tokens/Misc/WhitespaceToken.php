<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Misc;

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\Token;
use function Netmosfera\PHPCSSAST\match;
use function preg_replace;

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
    private $_text;

    /**
     * @var         String|NULL
     * `String|NULL`
     */
    private $_normalizedObject;

    /**
     * @param       String                                  $text
     * `String`
     * The whitespace sequence.
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
            match($other->_text, $this->_text);
    }

    /** @inheritDoc */
    public function normalize(): WhitespaceToken{
        if($this->_normalizedObject === NULL){
            $this->_normalizedObject = new WhitespaceToken(
                preg_replace(
                    '/' . SpecData::WHITESPACES_SEQS_SET . '/usD',
                    SpecData::WHITESPACE,
                    $this->_text
                )
            );
        }
        return $this->_normalizedObject;
    }
}
