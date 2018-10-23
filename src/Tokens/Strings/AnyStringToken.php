<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Strings;

use Netmosfera\PHPCSSAST\Tokens\EvaluableToken;
use Netmosfera\PHPCSSAST\Tokens\RootToken;

/**
 * A {@see AnyStringToken} is either a {@see StringToken} or a {@see BadStringToken}.
 */
interface AnyStringToken extends RootToken, EvaluableToken
{}
