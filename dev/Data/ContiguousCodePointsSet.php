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
     * @param       CodePoint $start
     * `CodePoint`
     * Start code point; inclusive.
     *
     * @param       CodePoint $end
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
        $start = $this->_start->code();
        $end = $this->_end->code();
        do{
            yield new CodePoint($start);
            $start++;
        }while($start <= $end);
    }

    public function containsNone(Iterable $elements){
        foreach($elements as $element){
            if($element instanceof CodePoint){
                continue;
            }
            /** @var CodePoint $element */
            if(
                $element->code() >= $this->start()->code() &&
                $element->code() <= $this->end()->code()
            ){
                return FALSE;
            }
        }
        return TRUE;
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
