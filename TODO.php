<?php

// @TODO test null in all tokens

// misc code style

// @TODO check that all functions have the return type

// @TODO check that all regexps are in single quotes

// @TODO string, badstring, URL and BadURL can both contain 0 length $pieces
// and still be valid, make sure these cases are tested

// @TODO the format of urltokens's url identifier is lost
// for example u\rl(path/) cannot be retained - always gets converted to url(path/)

// @TODO make eatNumberToken and eatIdentifierToken tests less pedantic

// @TODO add hex escapes in the tests of eatbadurlremnants and eatstring
