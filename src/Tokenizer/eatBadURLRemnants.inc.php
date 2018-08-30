<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\Tokenizer\Tools\Escapes\eatAnyEscape;
use Netmosfera\PHPCSSAST\Tokens\SubTokens\BadURLRemnants;
use Netmosfera\PHPCSSAST\Traverser;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function eatBadURLRemnants(Traverser $t): BadURLRemnants{
    $pieces = [];

    LOOP:

    if($t->isEOF()){
        return new BadURLRemnants($pieces, TRUE);
    }

    if(has($t->eatStr(")"))){
        return new BadURLRemnants($pieces);
    }

    $et = $t->createBranch();
    if(has($et->eatStr("\\"))){
        $pieces[] = eatAnyEscape($et);
        $t->importBranch($et);
        goto LOOP;
    }

    $piece = $t->eatExp('[^\\)\\\\]+'); // var_export(preg_quote(")\\"));
    assert($piece !== NULL);
    $pieces[] = $piece;

    goto LOOP;
}
