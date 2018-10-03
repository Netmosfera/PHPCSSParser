<?php declare(strict_types = 1);

// phpcs:disable

namespace Netmosfera\PHPCSSAST;

class SpecData
{
    public const DIGITS_REGEX_SET                   = '0-9';
    public const HEX_DIGITS_REGEX_SET               = '0-9a-fA-F';
    public const NAME_STARTERS_REGEX_SET            = '_-_a-zA-Z-􏿿\\x{0}-\\x{0}';
    public const NAME_COMPONENTS_REGEX_SET          = '\\--\\-_-_a-zA-Z-􏿿\\x{0}-\\x{0}0-9';
    public const WHITESPACES_REGEX_SET              = ' - 	-
-';
    public const WHITESPACES_REGEX_SEQS             = '
||
||	| ';
    public const NEWLINES_REGEX_SET                 = '
-
-';
    public const NEWLINES_REGEX_SEQS                = '
||
|';
    public const ENCODED_CP_ESCAPE_REGEX_SET        = '\\x{0}-	--\\/g-􏿿\\:-@G-`';
    public const STRING_BIT_CPS_REGEX_SET           = '\\]-􏿿\\x{0}-	--\\[';
    public const URL_TOKEN_BIT_CPS_REGEX_SET        = '	-	-􏿿\\x{0}-\\x{0} -\\!#-&\\*-\\[\\]-~';
    public const URL_TOKEN_BIT_NOT_CPS_REGEX_SET    = '-
-"-"\'-\\)\\\\-\\\\-';
    public const BAD_URL_REMNANTS_BIT_CPS_REGEX_SET = '\\x{0}-\\(\\*-\\[\\]-􏿿';
    public const NEWLINE                            = '
';
    public const REPLACEMENT_CHARACTER              = '�';
}

// phpcs:enable
