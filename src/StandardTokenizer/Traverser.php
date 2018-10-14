<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use Error;
use function preg_quote;
use function preg_last_error;

class Traverser
{
    private $originator;

    private $data;

    private $index;

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
        $this->setOffset(0);
    }

    private function setOffset(Int $offset){
        $this->index = $offset;
        if($this->showPreview){
            $this->preview = substr($this->data, $this->index);
        }
    }

    public function savepoint(){
        return $this->index;
    }

    public function rollback($savepoint){
        $this->setOffset($savepoint);
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

    public function isEOF(): Bool{
        return $this->index === strlen($this->data);
    }

    public function escapeRegexp(String $string): String{
        return preg_quote($string, "/");
    }

    public function eatPattern(String $pattern): ?String{
        $pattern = '/\G(' . $pattern . ')/sD';
        $result = preg_match($pattern, $this->data, $matches, 0, $this->index);
        if($result === FALSE){
            throw new Error("PCRE ERROR: " . preg_last_error());
        }
        if($result === 1){
            $this->setOffset($this->index + strlen($matches[0]));
            return $matches[0];
        }
        return NULL;
    }

    public function eatLength(Int $length): ?String{
        $trim = substr($this->data, $this->index, $length * 4);
        $string = mb_substr($trim, 0, $length);
        if(mb_strlen($string) === $length){
            $this->setOffset($this->index + strlen($string));
            return $string;
        }
        return NULL;
    }

    public function eatString(String $string): ?String{
        $length = strlen($string);
        if(substr($this->data, $this->index, $length) === $string){
            $this->setOffset($this->index + $length);
            return $string;
        }
        return NULL;
    }

    public function eatAll(): String{
        return $this->eatPattern(".*");
    }
}
