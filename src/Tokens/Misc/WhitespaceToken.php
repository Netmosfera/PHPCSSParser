<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Misc;

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\RootToken;
use function Netmosfera\PHPCSSAST\match;
use function preg_replace;

/**
 * A {@see WhitespaceToken} is a sequence of one or more whitespace code points.
 *
 * The whitespace code points are defined in {@see SpecData::WHITESPACES_SET}.
 */
class WhitespaceToken implements RootToken
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
     * @var         Int
     * `Int`
     */
    private $_newlineCount;

    /**
     * @param       String $text
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
    public function newlineCount(): Int{
        if($this->_newlineCount === NULL){
            $this->_newlineCount = preg_match_all(
                "/(" . SpecData::NEWLINES_REGEX_SEQS. ")/usD",
                $this->_text
            );
        }
        return $this->_newlineCount;
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
                    '/' . SpecData::NEWLINES_REGEX_SEQS . '/usD',
                    SpecData::NEWLINE,
                    $this->_text
                )
            );
        }
        return $this->_normalizedObject;
    }
}
