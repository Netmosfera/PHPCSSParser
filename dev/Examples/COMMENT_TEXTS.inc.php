<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\Examples;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function COMMENT_TEXTS(){
    $sequences[] = "";
    $sequences[] = "comment text";
    $sequences[] = " \t \n \r \r\n \f ";
    $sequences[] = "sample \u{2764} string";
    $sequences[] = "comment terminating with incomplete comment-end *";
    $sequences[] = "comment text can contain /* without causing a parse error";
    return $sequences;
}
