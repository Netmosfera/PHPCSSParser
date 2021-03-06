<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Names\URLs;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;

/**
 * A {@see BadURLToken} is a {@see URLToken} that terminates with invalid data.
 */
class BadURLToken implements AnyURLToken
{
    /**
     * @var         IdentifierToken
     * `IdentifierToken`
     */
    private $_identifier;

    /**
     * @var         WhitespaceToken|NULL
     * `WhitespaceToken|NULL`
     */
    private $_whitespaceBefore;

    /**
     * @var         URLBitToken[]|ValidEscapeToken[]
     * `Array<Int, URLBitToken|ValidEscapeToken>`
     */
    private $_pieces;

    /**
     * @var         BadURLRemnantsToken
     * `BadURLRemnantsToken`
     */
    private $_remnants;

    /**
     * @param       IdentifierToken $identifier
     * `IdentifierToken`
     * @TODOC
     *
     * @param       WhitespaceToken|NULL $whitespaceBefore
     * `WhitespaceToken|NULL`
     * @TODOC
     *
     * @param       URLBitToken[]|ValidEscapeToken[] $pieces
     * `Array<Int, URLBitToken|ValidEscapeToken>`
     * @TODOC
     *
     * @param       BadURLRemnantsToken $badURLRemnants
     * `BadURLRemnantsToken`
     * @TODOC
     */
    public function __construct(
        IdentifierToken $identifier,
        ?WhitespaceToken $whitespaceBefore,
        array $pieces,
        BadURLRemnantsToken $badURLRemnants
    ){
        $this->_identifier = $identifier;
        $this->_whitespaceBefore = $whitespaceBefore;
        $this->_pieces = $pieces;
        $this->_remnants = $badURLRemnants;
    }

    /** @inheritDoc */
    public function __toString(): String{ // @memo
        return
            $this->_identifier . "(" .
            $this->_whitespaceBefore .
            implode("", $this->_pieces) .
            $this->_remnants;
    }

    /** @inheritDoc */
    public function isParseError(): Bool{
        return TRUE;
    }

    /** @inheritDoc */
    public function newlineCount(): Int{ // @memo
        $count = $this->_remnants->newlineCount();
        foreach($this->_pieces as $piece){
            $count += $piece->newlineCount();
        }
        return $count;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->_identifier, $other->_identifier) &&
            match($this->_whitespaceBefore, $other->_whitespaceBefore) &&
            match($this->_pieces, $other->_pieces) &&
            match($this->_remnants, $other->_remnants);
    }

    /**
     * @TODOC
     *
     * @return      IdentifierToken
     * `IdentifierToken`
     * @TODOC
     */
    public function identifier(): IdentifierToken{
        return $this->_identifier;
    }

    /**
     * @TODOC
     *
     * @return      WhitespaceToken|NULL
     * `WhitespaceToken|NULL`
     * @TODOC
     */
    public function whitespaceBefore(): ?WhitespaceToken{
        return $this->_whitespaceBefore;
    }

    /**
     * @TODOC
     *
     * @return      URLBitToken[]|ValidEscapeToken[]
     * `Array<Int, URLBitToken|ValidEscapeToken>`
     * @TODOC
     */
    public function pieces(): array{
        return $this->_pieces;
    }

    /**
     * @TODOC
     *
     * @return      BadURLRemnantsToken
     * `BadURLRemnantsToken`
     * @TODOC
     */
    public function remnants(): BadURLRemnantsToken{
        return $this->_remnants;
    }
}
