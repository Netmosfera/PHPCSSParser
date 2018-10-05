<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use Error;
use function preg_quote;
use function preg_last_error;

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

    public function savepoint(){
        return $this->offset;
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
        $this->offset = $traverser->offset;
        $this->preview = $traverser->preview;
    }

    public function createBranch(): Traverser{
        return clone $this;
    }

    private function setOffset(Int $offset){
        $this->offset = $offset;
        if($this->showPreview){
            $this->preview = substr($this->data, $this->offset);
        }
    }

    public function isEOF(): Bool{
        return $this->offset === strlen($this->data);
    }

    public function escapeRegexp(String $string): String{
        return preg_quote($string, "/");
    }

    private function execRegexp(String $regexp): ?String{
        $result = preg_match(
            "/\G(" . $regexp . ")/usD",
            $this->data,
            $matches,
            0,
            $this->offset
        );

        // @codeCoverageIgnoreStart
        if($result === FALSE){
            throw new Error("PCRE ERROR: " . preg_last_error());
        }
        // @codeCoverageIgnoreEnd

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
        $length = strlen($string);
        if(substr($this->data, $this->offset, $length) === $string){
            $this->setOffset($this->offset + $length);
            return $string;
        }
        return NULL;
    }

    public function eatAll(): String{
        return $this->eatExp(".*");
    }
}
