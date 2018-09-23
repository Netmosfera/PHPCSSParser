<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer\Data;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function dechex;
use PHPToolBucket\CompressedIntSet\CompressedIntSet;
use IteratorAggregate;
use Iterator;
use IntlChar;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class CompressedCodePointSet implements IteratorAggregate
{
    private $data;

    function __construct(){
        $this->data = new CompressedIntSet();
    }

    function equals($other): Bool{
        return
            $other instanceof CompressedCodePointSet &&
            $this->data->equals($other->data);
    }

    function getRegExp(): String{
        $b = "";
        foreach($this->data->ranges as $start => $end){
            $b .= sprintf('\x{%s}-\x{%s}', dechex($start), dechex($end));
        }
        return $b;
    }

    function getIterator(): Iterator{
        foreach($this->data->ranges as $start => $end){
            do{
                yield IntlChar::chr($start);
                $start++;
            }while($start <= $end);
        }
    }

    function contains(String $codePoint){
        return $this->data->contains(IntlChar::ord($codePoint));
    }

    /** @return ContiguousCodePointsSet[] */
    function getRanges(){
        foreach($this->data->ranges as $start => $end){
            yield new ContiguousCodePointsSet(new CodePoint($start), new CodePoint($end));
        }
    }

    function addAll(Iterable/* Mixed, CodePoint */ $elements){
        if($elements instanceof ContiguousCodePointsSet){
            $this->data->addRange($elements->getStart()->getCode(), $elements->getEnd()->getCode());
        }elseif($elements instanceof CompressedCodePointSet){
            foreach($elements->getRanges() as $range){
                $this->addAll($range);
            }
        }else{
            foreach($elements as $element){
                assert($element instanceof CodePoint);
                $this->addAll(new ContiguousCodePointsSet($element, $element));
            }
        }
    }

    function add(CodePoint $codePoint){
        $this->addAll([$codePoint]);
    }

    function removeAll(Iterable $elements){
        if($elements instanceof ContiguousCodePointsSet){
            $this->data->removeRange($elements->getStart()->getCode(), $elements->getEnd()->getCode());
        }elseif($elements instanceof CompressedCodePointSet){
            foreach($elements->getRanges() as $range){
                $this->removeAll($range);
            }
        }else{
            foreach($elements as $element){
                if($element instanceof CodePoint){
                    $this->removeAll(new ContiguousCodePointsSet($element, $element));
                }
            }
        }
    }

    function remove($element){
        $this->removeAll([$element]);
    }

    function selectAll(){
        $this->data->addRange(0, IntlChar::CODEPOINT_MAX);
    }
}
