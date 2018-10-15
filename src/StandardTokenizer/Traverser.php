<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use Error;
use function preg_last_error;
use const PREG_UNMATCHED_AS_NULL;

class Traverser
{
    private $originator;

    public $data;

    public $index;

    private $debugIndex;

    private $showPreview;

    private $preview;

    /**
     * @param       String $data
     * {@TODOC}
     *
     * @param       Bool $showPreview
     * If set to `TRUE` will preview the result of `substr($data, $offset)` in
     * `$preview`. Enabling this has no purpose except for debugging. Keeping it
     * enabled will slow down the operations, so it must be enabled only if
     * needed.
     */
    public function __construct(String $data, Bool $showPreview = FALSE){
        $this->originator = $this;
        $this->data = $data;
        $this->showPreview = $showPreview;
        $this->preview = NULL;
        if($this->showPreview){
            unset($this->index); // enables __get and __set
        }
        $this->index = 0;
    }

    public function __get($property){
        if($property === "index"){
            return $this->debugIndex;
        }else{
            throw new Error();
        }
    }

    public function __set($property, $value){
        if($property === "index"){
            $this->debugIndex = $value;
            $this->preview = substr($this->data, $value);
        }else{
            throw new Error();
        }
    }

    public function rollback($savepoint){
        $this->index = $savepoint;
    }

    public function importBranch(Traverser $traverser){
        if($this->originator !== $traverser->originator){
            throw new Error(
                "Branch can be imported only from a " .
                "traverser with the same origin"
            );
        }
        $this->index = $traverser->index;
        $this->preview = $traverser->preview;
    }

    public function createBranch(): Traverser{
        return clone $this;
    }

    public function eatPattern(String $pattern): ?String{
        $pattern = '/\G(' . $pattern . ')/sD';
        $result = preg_match($pattern, $this->data, $matches, 0, $this->index);
        if($result === FALSE){
            throw new Error("PCRE ERROR: " . preg_last_error());
        }
        if($result === 1){
            $this->index = $this->index + strlen($matches[0]);
            return $matches[0];
        }
        return NULL;
    }

    public function eatPatterns(String $patterns): ?array{
        $patterns = '/\G(' . $patterns . ')/sDx';
        $result = preg_match($patterns, $this->data, $matches, PREG_UNMATCHED_AS_NULL, $this->index);
        if($result === FALSE){
            throw new Error("PCRE ERROR: " . preg_last_error());
        }
        if($result === 1){
            $this->index = $this->index + strlen($matches[0]);
            return $matches;
        }
        return NULL;
    }

    public function eatLength(Int $length): ?String{
        $trim = substr($this->data, $this->index, $length * 4);
        $string = mb_substr($trim, 0, $length);
        if(mb_strlen($string) === $length){
            $this->index = $this->index + strlen($string);
            return $string;
        }
        return NULL;
    }

    public function eatString(String $string): ?String{
        $length = strlen($string);
        if(substr($this->data, $this->index, $length) === $string){
            $this->index = $this->index + $length;
            return $string;
        }
        return NULL;
    }

    public function eatAll(): String{
        return $this->eatPattern(".*");
    }
}
