<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Names\URLs;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Token;

/**
 * @TODOC
 */
class BadURLRemnantsToken implements Token
{
    /**
     * @var         BadURLRemnantsBitToken[]|EscapeToken[]
     * @TODOC
     */
    private $_pieces;

    /**
     * @var         Bool
     * `Bool`
     * @TODOC
     */
    private $_EOFTerminated;

    /**
     * @var         Int
     * `Int`
     */
    private $_newlineCount;

    /**
     * @param       BadURLRemnantsBitToken[]|EscapeToken[] $pieces
     * `Array<Int, BadURLRemnantsBitToken|EscapeToken>`
     * @TODOC
     *
     * @param       Bool $EOFTerminated
     * `Bool`
     * @TODOC
     */
    public function __construct(array $pieces, Bool $EOFTerminated){
        $this->_pieces = $pieces;
        $this->_EOFTerminated = $EOFTerminated;
    }

    /** @inheritDoc */
    public function __toString(): String{
        return
            implode("", $this->_pieces) .
            ($this->_EOFTerminated ? "" : ")");
    }

    /** @inheritDoc */
    public function newlineCount(): Int{
        if($this->_newlineCount === NULL){
            $count = 0;
            foreach($this->_pieces as $piece){
                $count += $piece->newlineCount();
            }
            $this->_newlineCount = $count;
        }
        return $this->_newlineCount;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($this->_pieces, $other->_pieces) &&
            match($this->_EOFTerminated, $other->_EOFTerminated);
    }

    /**
     * @TODOC
     *
     * @return      BadURLRemnantsBitToken[]|EscapeToken[]
     * `Array<Int, BadURLRemnantsBitToken|EscapeToken>`
     * @TODOC
     */
    public function pieces(): array{
        return $this->_pieces;
    }

    /**
     * @TODOC
     *
     * @return      Bool
     * `Bool`
     * @TODOC
     */
    public function EOFTerminated(): Bool{
        return $this->_EOFTerminated;
    }
}
