<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Error;
use function preg_quote;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class Traverser
{
    private $originator;

    private $data;

    private $offset;

    private $showPreview;

    private $preview;

    /**
     * @param       String                                  $data
     * {@TODOC}
     *
     * @param       Bool                                    $showPreview
     * If set to `TRUE` will preview the result of `substr($data, $offset)` in `$preview`.
     * Enabling this has no purpose except for debugging. Keeping it enabled will slow down
     * the operations, so it must be enabled only if needed.
     */
    public function __construct(String $data, Bool $showPreview = FALSE){
        $this->originator = $this;
        $this->data = $data;
        $this->showPreview = $showPreview;
        $this->preview = NULL;
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
        $this->preview = $traverser->preview;
    }

    public function createBranch(): Traverser{
        return clone $this;
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    private function setOffset(Int $offset){
        $this->offset = $offset;
        if($this->showPreview){
            $this->preview = substr($this->data, $this->offset);
        }
    }

    public function isEOF(): Bool{
        return $this->offset === strlen($this->data);
    }

    public function isNotEOF(): Bool{
        return $this->isEOF() === FALSE;
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    public function escapeRegexp(String $string): String{
        return preg_quote($string, "/");
    }

    private function execRegexp(String $regexp): ?String{
        $result = preg_match(
            "/^(" . $regexp . ")/su",
            substr($this->data, $this->offset), // @TODO this allocates a new string every time, it should work with offsets, not on trimmed strings
            $matches
        );

        if($result === 1){
            $this->setOffset($this->offset + strlen($matches[0]));
            return $matches[0];
        }

        return NULL;
    }

    public function eatExp(String $regexp): ?String{
        return $this->execRegexp($regexp);
    }

    public function eatStr(String $string): ?String{
        return $this->eatExp($this->escapeRegexp($string));
    }

    public function eatAll(): String{
        return $this->eatExp(".*");
    }
}
