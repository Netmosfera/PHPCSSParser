<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Error;
use function mb_substr;
use function preg_quote;
use function preg_last_error;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class Traverser
{
    private $unicode;

    private $originator;

    private $wholeData;

    private $offset;

    private $remainingData;

    public function __construct(String $wholeData, Bool $unicode = TRUE){
        $this->unicode = $unicode;
        $this->originator = $this;
        $this->wholeData = $wholeData;
        $this->remainingData = $wholeData;
        $this->setOffset(0);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    public function savepoint(){
        return $this->offset;
    }

    public function rollback($savepoint){
        $this->setOffset($savepoint);
    }

    public function importBranch(Traverser $traverser){
        if($this->originator !== $traverser->originator){
            throw new Error("Branch can be imported only from a traverser with the same origin");
        }
        $this->offset = $traverser->offset;
        $this->remainingData = $traverser->remainingData;
    }

    public function createBranch(): Traverser{
        return clone $this;
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    private function setOffset(Int $offset){
        $this->offset = $offset;
        $this->remainingData = substr($this->wholeData, $this->offset);
    }

    private function advanceOffset(Int $length){
        if($length < 0){ throw new Error(); }
        if($length === 0){ return; }
        $this->offset += $length;
        $this->remainingData = substr($this->remainingData, $length);
    }

    public function isEOF(): Bool{
        return $this->remainingData === "";
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    public function escapeRegexp(String $string): String{
        return preg_quote($string, "/");
    }

    private function execRegexp(String $regexp): ?String{
        $result = preg_match("/^(" . $regexp . ")/s" . ($this->unicode? "u" : ""), $this->remainingData, $matches);

        if($result === FALSE){
            throw new Error("PCRE ERROR: " . preg_last_error());
        }

        if($result === 1){
            $this->advanceOffset(strlen($matches[0]));
            return $matches[0];
        }

        return NULL;
    }

    public function eatExp(String $regexp): ?String{
        return $this->execRegexp($regexp);
    }

    public function eatStr(String $string): ?String{
        $length = strlen($string);
        if(substr($this->remainingData, 0, $length) === $string){
            $this->advanceOffset($length);
            return $string;
        }
        return NULL;
    }

    public function eatLength(Int $length): ?String{
        if($length < 0){
            throw new Error("Invalid length");
        }
        if($length === 0){
            return "";
        }
        $string = mb_substr($this->remainingData, 0, $length);
        if(mb_strlen($string) === $length){
            $this->advanceOffset(strlen($string));
            return $string;
        }else{
            return NULL;
        }
    }

    public function eatAll(): String{
        return $this->eatExp(".*");
    }
}
