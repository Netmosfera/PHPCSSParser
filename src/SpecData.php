<?php declare(strict_types = 1);

// phpcs:disable

namespace Netmosfera\PHPCSSAST;

class SpecData
{
    public $DIGITS_REGEX_SET                   = '\\x{30}-\\x{39}';
    public $HEX_DIGITS_REGEX_SET               = '\\x{30}-\\x{39}\\x{61}-\\x{66}\\x{41}-\\x{46}';
    public $NAME_STARTERS_REGEX_SET            = '\\x{5f}-\\x{5f}\\x{61}-\\x{7a}\\x{41}-\\x{5a}\\x{80}-\\x{10ffff}\\x{0}-\\x{0}';
    public $NAME_COMPONENTS_REGEX_SET          = '\\x{2d}-\\x{2d}\\x{5f}-\\x{5f}\\x{61}-\\x{7a}\\x{41}-\\x{5a}\\x{80}-\\x{10ffff}\\x{0}-\\x{0}\\x{30}-\\x{39}';
    public $NAME_STARTERS_BYTES_REGEX_SET      = '\\x{5f}-\\x{5f}\\x{61}-\\x{7a}\\x{41}-\\x{5a}\\x{0}-\\x{0}\\x{80}-\\x{ff}';
    public $NAME_COMPONENTS_BYTES_REGEX_SET    = '\\x{2d}-\\x{2d}\\x{5f}-\\x{5f}\\x{61}-\\x{7a}\\x{41}-\\x{5a}\\x{0}-\\x{0}\\x{30}-\\x{39}\\x{80}-\\x{ff}';
    public $WHITESPACES_REGEX_SET              = '\\x{20}-\\x{20}\\x{9}-\\x{a}\\x{c}-\\x{d}';
    public $WHITESPACES_REGEX_SEQS             = '
||
||	| ';
    public $NEWLINES_REGEX_SET                 = '\\x{a}-\\x{a}\\x{c}-\\x{d}';
    public $NEWLINES_REGEX_SEQS                = '
||
|';
    public $ENCODED_CP_ESCAPE_REGEX_SET        = '\\x{0}-\\x{9}\\x{b}-\\x{b}\\x{e}-\\x{2f}\\x{67}-\\x{10ffff}\\x{3a}-\\x{40}\\x{47}-\\x{60}';
    public $STRING_BIT_CPS_REGEX_SET           = '\\x{5d}-\\x{10ffff}\\x{0}-\\x{9}\\x{b}-\\x{b}\\x{e}-\\x{5b}';
    public $URL_TOKEN_BIT_CPS_REGEX_SET        = '\\x{80}-\\x{10ffff}\\x{0}-\\x{0}\\x{9}-\\x{9}\\x{20}-\\x{21}\\x{23}-\\x{26}\\x{2a}-\\x{5b}\\x{5d}-\\x{7e}';
    public $URL_TOKEN_BIT_NOT_CPS_REGEX_SET    = '\\x{1}-\\x{8}\\x{a}-\\x{1f}\\x{22}-\\x{22}\\x{27}-\\x{29}\\x{5c}-\\x{5c}\\x{7f}-\\x{7f}';
    public $BAD_URL_REMNANTS_BIT_CPS_REGEX_SET = '\\x{0}-\\x{28}\\x{2a}-\\x{5b}\\x{5d}-\\x{10ffff}';
    public $NEWLINE                            = '
';
    public $REPLACEMENT_CHARACTER              = '�';

    public static $instance;
}

SpecData::$instance = new SpecData();

// phpcs:enable
