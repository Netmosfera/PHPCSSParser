<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes\MainNodes;

use Netmosfera\PHPCSSAST\Nodes\Components\Component;
use Netmosfera\PHPCSSAST\Nodes\Components\CurlySimpleBlockComponent;
use function Netmosfera\PHPCSSAST\match;

/**
 * A {@see QualifiedRuleNode} is a list of {@see Component}s constituting the
 * {@see QualifiedRuleNode}'s prelude, followed by a
 * {@see CurlySimpleBlockComponent}.
 *
* The prelude does not start with a sequence of whitespaces and comments.
 */
class QualifiedRuleNode
{
    /**
     * @var         Component[]
     * `Array<Int, Component>`
     * @TODOC
     */
    private $_prelude;

    /**
     * @var         CurlySimpleBlockComponent
     * `CurlySimpleBlockComponent`
     * @TODOC
     */
    private $_terminator;

    /**
     * @param       Component[] $prelude
     * `Array<Int, Component>`
     * @TODOC
     *
     * @param       CurlySimpleBlockComponent $terminator
     * `CurlySimpleBlockComponent`
     * @TODOC
     */
    public function __construct(
        array $prelude,
        CurlySimpleBlockComponent $terminator
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
