<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Names\URLs;

use Netmosfera\PHPCSSAST\Nodes\ComponentValues\ComponentValue;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierLikeToken;

/**
 * A {@see AnyURLToken} is a {@see URLToken} or a {@see BadURLToken}.
 */
interface AnyURLToken extends IdentifierLikeToken, ComponentValue
{}
