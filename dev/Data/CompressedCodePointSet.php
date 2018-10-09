<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\Data;

use phpDocumentor\Reflection\Types\Iterable_;
use PHPToolBucket\CompressedIntSet\CompressedIntSet;
use IteratorAggregate;
use Iterator;
use IntlChar;

class CompressedCodePointSet implements IteratorAggregate
{
    private $_data;

    public function __construct(){
        $this->_data = new CompressedIntSet();
    }

    public function equals($other): Bool{
        return
            $other instanceof CompressedCodePointSet &&
            $this->_data->equals($other->_data);
    }

    public function count(): Int{
        $count = 0;
        foreach($this as $c){
            $count++;
        }
        return $count;
    }

    public function regexp(): String{
        $regexp = "";
        foreach($this->ranges() as $range){
            $regexp .= $range->regexp();
        }
        return $regexp;
    }

    public function getIterator(): Iterator{
        foreach($this->_data->ranges as $start => $end){
            do{
                yield new CodePoint($start);
                $start++;
            }while($start <= $end);
        }
    }

    public function contains($codePoint): Bool{
        if($codePoint instanceof CodePoint === FALSE){
            return FALSE;
        }
        /** @var CodePoint $codePoint */
        return $this->_data->contains($codePoint->code());
    }

    public function containsAll(Iterable $elements): Bool{
        if($elements instanceof ContiguousCodePointsSet){
            foreach($this->_data->ranges as $eStart => $eEnd){
                if(
                    $elements->start()->code() >= $eStart &&
                    $elements->end()->code() <= $eEnd
                ){
                    return TRUE;
                }
            }
            return FALSE;
        }elseif($elements instanceof CompressedCodePointSet){
            foreach($elements->ranges() as $range){
                if($this->containsAll($range) === FALSE){
                    return FALSE;
                }
            }
            return TRUE;
        }else{
            foreach($elements as $element){
                if($this->contains($element) === FALSE){
                    return FALSE;
                }
            }
            return TRUE;
        }
    }

    public function containsNone(Iterable $elements): Bool{
        foreach($elements as $element){
            if($this->contains($element)){
                return FALSE;
            }
        }
        return TRUE;
    }

    /** @return ContiguousCodePointsSet[] */
    public function ranges(): Iterable{
        foreach($this->_data->ranges as $start => $end){
            yield new ContiguousCodePointsSet(
                new CodePoint($start),
                new CodePoint($end)
            );
        }
    }

    public function selectAll(){
        $this->_data->addRange(0, IntlChar::CODEPOINT_MAX);
    }

    //------------------------------------------------------------------------------------

    public function addAll(Iterable/* Mixed, CodePoint */ $elements){
        if($elements instanceof ContiguousCodePointsSet){
            $this->_data->addRange(
                $elements->start()->code(),
                $elements->end()->code()
            );
        }elseif($elements instanceof CompressedCodePointSet){
            foreach($elements->ranges() as $range){
                $this->addAll($range);
            }
        }else{
            foreach($elements as $element){
                assert($element instanceof CodePoint);
                $this->_data->add($element->code());
            }
        }
    }

    public function add(CodePoint $codePoint){
        $this->addAll([$codePoint]);
    }

    //------------------------------------------------------------------------------------

    public function removeAll(Iterable $elements){
        if($elements instanceof ContiguousCodePointsSet){
            $this->_data->removeRange(
                $elements->start()->code(),
                $elements->end()->code()
            );
        }elseif($elements instanceof CompressedCodePointSet){
            foreach($elements->ranges() as $range){
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
}
