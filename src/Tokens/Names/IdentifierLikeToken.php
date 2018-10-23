<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Names;

use Netmosfera\PHPCSSAST\Tokens\RootToken;

/**
 * A {@see IdentifierLikeToken} is an {@see IdentifierToken}, a {@see FunctionToken} or a
 * {@see URLToken}.
 */
interface IdentifierLikeToken extends RootToken
{}
