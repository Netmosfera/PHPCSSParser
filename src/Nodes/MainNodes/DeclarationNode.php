<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes\MainNodes;

use Netmosfera\PHPCSSAST\Nodes\Components\Component;
use Netmosfera\PHPCSSAST\Tokens\Misc\CommentToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use function Netmosfera\PHPCSSAST\match;

/**
 * A {@see DeclarationNode} is a {@see IdentifierToken} followed by a colon and a
 * list of {@see Component}s that constitute the definition.
 *
 * It is never surrounded by sequences of whitespaces and comments.
 */
class DeclarationNode
{
    /**
     * @var         IdentifierToken
     * `IdentifierToken`
     * @TODOC
     */
    private $_identifier;

    /**
     * @var         CommentToken[]|WhitespaceToken[]
     * `Array<Int, CommentToken|WhitespaceToken>`
     * @TODOC
     */
    private $_whitespaceBeforeColon;

    /**
     * @var         CommentToken[]|WhitespaceToken[]
     * `Array<Int, CommentToken|WhitespaceToken>`
     * @TODOC
     */
    private $_whitespaceAfterColon;

    /**
     * @var         Component[]
     * `Array<Int, Component>`
     * @TODOC
     */
    private $_definition;

    /**
     * @param       IdentifierToken $identifier
     * `IdentifierToken`
     * The identifier being defined.
     *
     * @param       WhitespaceToken[]|CommentToken[] $whitespaceBeforeColon
     * `Array<Int, WhitespaceToken|CommentToken>`
     * The sequence of whitespace and comments that appear after the identifier and before
     * the colon.
     *
     * @param       WhitespaceToken[]|CommentToken[] $whitespaceAfterColon
     * `Array<Int, WhitespaceToken|CommentToken>`
     * The sequence of whitespace and comments that appear after the colon and before the
     * definition.
     *
     * @param       Component[] $definition
     * `Array<Int, Component>`
     * A sequence of {@see Component}s not surrounded by any {@see WhitespaceToken}
     * or {@see CommentToken}.
     */
    public function __construct(
        IdentifierToken $identifier,
        array $whitespaceBeforeColon,
        array $whitespaceAfterColon,
        array $definition
    ){
        $this->_identifier = $identifier;
        $this->_whitespaceBeforeColon = $whitespaceBeforeColon;
        $this->_whitespaceAfterColon = $whitespaceAfterColon;
        $this->_definition = $definition;
    }

    /** @inheritDoc */
    public function __toString(): String{ // @memo
        return
            $this->_identifier .
            implode("", $this->_whitespaceBeforeColon) . ":" .
            implode("", $this->_whitespaceAfterColon) .
            implode("", $this->_definition);
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->_identifier, $this->_identifier) &&
            match($other->_whitespaceBeforeColon, $this->_whitespaceBeforeColon) &&
            match($other->_whitespaceAfterColon, $this->_whitespaceAfterColon) &&
            match($other->_definition, $this->_definition);
    }
}
