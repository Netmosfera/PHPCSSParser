<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\Data;

use function is_string;
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

    public function getIterator(): Iterator{
        foreach($this->texts as $text => $_){
            yield $text;
        }
    }

    public function getRegExp(): String{
        static $s = [
            "\x0",  "\x1",  "\x2",  "\x3",  "\x4",  "\x5",  "\x6", "\x7",  "\x8",
            "\x9",  "\xa",  "\xb",  "\xc",  "\xd",  "\xe",  "\xf",  "\x10", "\x11",
            "\x12", "\x13", "\x14", "\x15", "\x16", "\x17", "\x18", "\x19", "\x1a",
            "\x1b", "\x1c", "\x1d", "\x1e", "\x1f", "\x7f",
        ];
        static $r = [
            '\\x{0}',  '\\x{1}',  '\\x{2}',  '\\x{3}',  '\\x{4}',  '\\x{5}',  '\\x{6}',
            '\\x{7}',  '\\x{8}',  '\\x{9}',  '\\x{a}',  '\\x{b}',  '\\x{c}',  '\\x{d}',
            '\\x{e}',  '\\x{f}',  '\\x{10}', '\\x{11}', '\\x{12}', '\\x{13}', '\\x{14}',
            '\\x{15}', '\\x{16}', '\\x{17}', '\\x{18}', '\\x{19}', '\\x{1a}', '\\x{1b}',
            '\\x{1c}', '\\x{1d}', '\\x{1e}', '\\x{1f}', '\\x{7f}',
        ];

        $texts = [];
        foreach($this->texts as $text => $_){
            $texts[] = str_replace($s, $r, $text);
        }
        return implode("|", $texts);
    }
}
