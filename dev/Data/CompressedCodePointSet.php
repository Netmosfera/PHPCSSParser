<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\Data;

use function dechex;
use PHPToolBucket\CompressedIntSet\CompressedIntSet;
use IteratorAggregate;
use Iterator;
use IntlChar;

class CompressedCodePointSet implements IteratorAggregate
{
    private $data;

    public function __construct(){
        $this->data = new CompressedIntSet();
    }

    public function equals($other): Bool{
        return
            $other instanceof CompressedCodePointSet &&
            $this->data->equals($other->data);
    }

    public function getRegExp(): String{
        $b = "";
        foreach($this->data->ranges as $start => $end){
            $b .= sprintf('\x{%s}-\x{%s}', dechex($start), dechex($end));
        }
        return $b;
    }

    public function getIterator(): Iterator{
        foreach($this->data->ranges as $start => $end){
            do{
                yield IntlChar::chr($start);
                $start++;
            }while($start <= $end);
        }
    }

    public function contains(String $codePoint){
        return $this->data->contains(IntlChar::ord($codePoint));
    }

    /** @return ContiguousCodePointsSet[] */
    public function getRanges(){
        foreach($this->data->ranges as $start => $end){
            yield new ContiguousCodePointsSet(
                new CodePoint($start),
                new CodePoint($end)
            );
        }
    }

    public function addAll(Iterable/* Mixed, CodePoint */ $elements){
        if($elements instanceof ContiguousCodePointsSet){
            $this->data->addRange(
                $elements->getStart()->getCode(),
                $elements->getEnd()->getCode()
            );
        }elseif($elements instanceof CompressedCodePointSet){
            foreach($elements->getRanges() as $range){
                $this->addAll($range);
            }
        }else{
            foreach($elements as $element){
                assert($element instanceof CodePoint);
                $this->addAll(
                    new ContiguousCodePointsSet($element, $element)
                );
            }
        }
    }

    public function add(CodePoint $codePoint){
        $this->addAll([$codePoint]);
    }

    public function removeAll(Iterable $elements){
        if($elements instanceof ContiguousCodePointsSet){
            $this->data->removeRange(
                $elements->getStart()->getCode(),
                $elements->getEnd()->getCode()
            );
        }elseif($elements instanceof CompressedCodePointSet){
            foreach($elements->getRanges() as $range){
                $this->removeAll($range);
            }
        }else{
            foreach($elements as $element){
                if($element instanceof CodePoint){
                    $this->removeAll(
                        new ContiguousCodePointsSet($element, $element)
                    );
                }
            }
        }
    }

    public function remove($element){
        $this->removeAll([$element]);
    }

    public function selectAll(){
        $this->data->addRange(0, IntlChar::CODEPOINT_MAX);
    }
}
