<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\Data;

use Error;
use IntlChar;
use Iterator;
use IteratorAggregate;

class ContiguousCodePointsSet implements IteratorAggregate
{
    private $start;
    private $end;

    public function __construct(CodePoint $start, ?CodePoint $end = NULL){
        $end = $end ?? $start;

        if($start->getCode() > $end->getCode()){
            throw new Error("Invalid code point range");
        }

        $this->start = $start;
        $this->end = $end;
    }

    public function getIterator(): Iterator{
        for($o = $this->start; $o <= $this->end; $o++){
            yield IntlChar::chr($o);
        }
    }

    public function getRegExp(): String{
        return sprintf(
            '\x{%s}-\x{%s}',
            $this->start->getHexCode(),
            $this->end->getHexCode()
        );
    }

    public function getStart(): CodePoint{
        return $this->start;
    }

    public function getEnd(): CodePoint{
        return $this->end;
    }
}
