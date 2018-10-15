<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use Error;
use function preg_quote;
use function preg_last_error;
use const PREG_UNMATCHED_AS_NULL;

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
        $this->index = 0;
        if($this->showPreview){
            $this->preview = substr($this->data, $this->index);
        }
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
        $this->index = $savepoint;
        if($this->showPreview){
            $this->preview = substr($this->data, $this->index);
        }
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
            $this->index = $this->index + strlen($matches[0]);
            if($this->showPreview){
                $this->preview = substr($this->data, $this->index);
            }
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
            if($this->showPreview){
                $this->preview = substr($this->data, $this->index);
            }
            return $matches;
        }
        return NULL;
    }

    public function eatLength(Int $length): ?String{
        $trim = substr($this->data, $this->index, $length * 4);
        $string = mb_substr($trim, 0, $length);
        if(mb_strlen($string) === $length){
            $this->index = $this->index + strlen($string);
            if($this->showPreview){
                $this->preview = substr($this->data, $this->index);
            }
            return $string;
        }
        return NULL;
    }

    public function eatString(String $string): ?String{
        $length = strlen($string);
        if(substr($this->data, $this->index, $length) === $string){
            $this->index = $this->index + $length;
            if($this->showPreview){
                $this->preview = substr($this->data, $this->index);
            }
            return $string;
        }
        return NULL;
    }

    public function eatAll(): String{
        return $this->eatPattern(".*");
    }
}
