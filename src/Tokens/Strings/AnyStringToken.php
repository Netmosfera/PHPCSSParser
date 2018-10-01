<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Strings;

use Netmosfera\PHPCSSAST\Tokens\Token;

/**
 * A {@see AnyStringToken} is either a {@see StringToken} or a {@see BadStringToken}.
 */
interface AnyStringToken extends Token
{}
