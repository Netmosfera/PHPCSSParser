<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\FastTokenizer;

use const PREG_SPLIT_NO_EMPTY;
use function file_get_contents;
use const PREG_SPLIT_DELIM_CAPTURE;

class FastTokenizerPiece
{
    public $type;
    public $value;
}

class FastTokenizer
{
    public function tokenize(String $css){
        $pieces = preg_split(Data::SPLIT, $css, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        foreach($pieces as $pieceIndex => $piece){
            foreach(Data::RECOGNIZE_GROUPS as $nameOfGroup => $charactersInGroup){
                if(isset($charactersInGroup[$piece[0]])){
                    $pieces[$pieceIndex] = new FastTokenizerPiece();
                    $pieces[$pieceIndex]->type = $nameOfGroup;
                    $pieces[$pieceIndex]->value = $piece;
                    continue 2;
                }
            }
            $pieces[$pieceIndex] = new FastTokenizerPiece();
            $pieces[$pieceIndex]->type = "U";
            $pieces[$pieceIndex]->value = $piece;
        }

        /** @var FastTokenizerPiece[] $pieces */

        for($i = 0; $i < count($pieces); $i++){
            $piece = $pieces[$i];

        }
    }
}


require __DIR__ . "/../../vendor/autoload.php";

$x = new FastTokenizer();
$file = file_get_contents(__DIR__ . "/../../tmp/test.css");

$st = microtime(TRUE);
$x->tokenize($file);
var_dump(number_format(microtime(TRUE) - $st, 10)) . "\n";
