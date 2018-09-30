<?php declare(strict_types = 1);

// phpcs:disable

namespace Netmosfera\PHPCSSAST;

class SpecData
{
    public const DIGITS_SET                = "\x{30}-\x{39}";
    public const HEX_DIGITS_SET            = "\x{30}-\x{39}\x{61}-\x{66}\x{41}-\x{46}";
    public const LETTERS_SET               = "\x{61}-\x{7a}\x{41}-\x{5a}";
    public const LETTERS_LC_SET            = "\x{61}-\x{7a}";
    public const LETTERS_UC_SET            = "\x{41}-\x{5a}";
    public const NAME_STARTERS_SET         = "\x{5f}-\x{5f}\x{61}-\x{7a}\x{41}-\x{5a}\x{80}-\x{10ffff}\x{0}-\x{0}";
    public const NAME_ITEMS_SET            = "\x{2d}-\x{2d}\x{5f}-\x{5f}\x{61}-\x{7a}\x{41}-\x{5a}\x{80}-\x{10ffff}\x{0}-\x{0}\x{30}-\x{39}";
    public const WHITESPACES_SET           = "\x{20}-\x{20}\x{9}-\x{a}\x{c}-\x{d}";
    public const WHITESPACES_SEQS_SET      = "\x{d}\x{a}|\x{d}|\x{a}|\x{c}|\x{9}| ";
    public const NEWLINES_SET              = "\x{a}-\x{a}\x{c}-\x{d}";
    public const NEWLINES_SEQS_SET         = "\x{d}\x{a}|\x{d}|\x{a}|\x{c}";
    public const NON_ASCII_SET             = "\x{80}-\x{10ffff}\x{0}-\x{0}";
    public const NON_PRINTABLES_SET        = "\x{0}-\x{8}\x{b}-\x{b}\x{e}-\x{1f}\x{7f}-\x{7f}";
    public const STRING_DELIMITERS_SET     = "\x{22}-\x{22}\x{27}-\x{27}";
    public const VALID_ESCAPE_STARTERS_SET = "\x{0}-\x{9}\x{b}-\x{b}\x{e}-\x{10ffff}";
    public const ENCODED_ESCAPE_SET        = "\x{0}-\x{9}\x{b}-\x{b}\x{e}-\x{2f}\x{67}-\x{10ffff}\x{3a}-\x{40}\x{47}-\x{60}";
    public const STRING_BIT_CP_SET         = "\x{5d}-\x{10ffff}\x{0}-\x{9}\x{b}-\x{b}\x{e}-\x{5b}";
    public const URLTOKEN_BIT_CP_SET       = "\x{9}-\x{9}\x{80}-\x{10ffff}\x{0}-\x{0}\x{20}-\x{21}\x{23}-\x{26}\x{2a}-\x{5b}\x{5d}-\x{7e}";
    public const URLTOKEN_BIT_CP_NOT_SET   = "\x{1}-\x{8}\x{a}-\x{1f}\x{22}-\x{22}\x{27}-\x{29}\x{5c}-\x{5c}\x{7f}-\x{7f}";
    public const BAD_URL_REMNANTS_BIT_SET  = "\x{0}-\x{28}\x{2a}-\x{5b}\x{5d}-\x{10ffff}";
    public const REPLACEMENT_CHARACTER = "\u{FFFD}"; 
    public const WHITESPACE = " "; 
}

// phpcs:enable
