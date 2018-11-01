<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes\Components;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Nodes\ComponentValues\ComponentValue;
use Netmosfera\PHPCSSAST\Nodes\ComponentValues\CurlySimpleBlockComponentValue;

/**
 * A {@see QualifiedRuleNode} is a list of {@see ComponentValue}s constituting the
 * {@see QualifiedRuleNode}'s prelude, followed by a
 * {@see CurlySimpleBlockComponentValue}.
 *
* The prelude does not start with a sequence of whitespaces and comments.
 */
class QualifiedRuleNode
{
    /**
     * @var         ComponentValue[]
     * `Array<Int, ComponentValue>`
     * @TODOC
     */
    private $_prelude;

    /**
     * @var         CurlySimpleBlockComponentValue
     * `CurlySimpleBlockComponentValue`
     * @TODOC
     */
    private $_terminator;

    /**
     * @param       ComponentValue[] $prelude
     * `Array<Int, ComponentValue>`
     * @TODOC
     *
     * @param       CurlySimpleBlockComponentValue $terminator
     * `CurlySimpleBlockComponentValue`
     * @TODOC
     */
    public function __construct(
        array $prelude,
        CurlySimpleBlockComponentValue $terminator
    ){
        $this->_prelude = $prelude;
        $this->_terminator = $terminator;
    }

    /** @inheritDoc */
    public function __toString(): String{ // @memo
        return implode("", $this->_prelude) . $this->_terminator;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->_prelude, $this->_prelude) &&
            match($other->_terminator, $this->_terminator);
    }
}
