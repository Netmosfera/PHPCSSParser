<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokens\Numbers;

use Netmosfera\PHPCSSAST\Nodes\Components\Component;
use Netmosfera\PHPCSSAST\Tokens\RootToken;

/**
 * A {@see NumericToken} is a plain number, a measurement, or a percentage.
 */
interface NumericToken extends RootToken, Component
{}
