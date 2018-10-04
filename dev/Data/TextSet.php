<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\Data;

use function is_string;
use function array_key_exists;
use IteratorAggregate;
use TypeError;
use Iterator;

class TextSet implements IteratorAggregate
{
    private $texts;

    public function __construct(Array $texts){
        $textSet = [];
        foreach($texts as $text){
            if(is_string($text) === FALSE){
                throw new TypeError();
            }
            $textSet[$text] = TRUE;
        }

        // lengthier texts appear first
        uksort($textSet, function($a , $b){
            $aLength = mb_strlen($a);
            $bLength = mb_strlen($b);
            return $bLength - $aLength;
        });

        $this->texts = $textSet;
    }

    public function contains($text): Bool{
        if(is_string($text) === FALSE){
            return FALSE;
        }
        return array_key_exists($text, $this->texts);
    }

    public function getIterator(): Iterator{
        foreach($this->texts as $text => $_){
            yield $text;
        }
    }

    public function getRegExp(): String{
        return implode("|", array_keys($this->texts));
    }
}
