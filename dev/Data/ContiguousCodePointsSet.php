<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\Data;

use Error;
use IntlChar;
use Iterator;
use IteratorAggregate;

class ContiguousCodePointsSet implements IteratorAggregate
{
    private $_start;

    private $_end;

    /**
     * @param       CodePoint                               $start
     * `CodePoint`
     * Start code point; inclusive.
     *
     * @param       CodePoint                               $end
     * `CodePoint`
     * End code point; inclusive.
     */
    public function __construct(CodePoint $start, CodePoint $end){
        if($start->code() > $end->code()){
            throw new Error("Invalid code point range");
        }
        $this->_start = $start;
        $this->_end = $end;
    }

    public function getIterator(): Iterator{
        for($o = $this->_start; $o <= $this->_end; $o++){
            yield IntlChar::chr($o);
        }
    }

    public function regexp(): String{
        return $this->_start->regexp() . "-" . $this->_end->regexp();
    }

    public function start(): CodePoint{
        return $this->_start;
    }

    public function end(): CodePoint{
        return $this->_end;
    }
}
