<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev\Examples;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function getWhitespaceSeqsSet(){
    return [
        " ",
        "\t",
        "\r",
        "\n",
        "\r\n",
        "\f",
        "\t\t\t",
        "        ",
        "     \t       \r        \n      \f         \r\n     ",
    ];
}
