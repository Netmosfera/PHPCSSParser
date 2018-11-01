<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes\ComponentValues;

interface SimpleBlockComponentValue extends ComponentValue
{
    public function openDelimiter(): String;

    public function closeDelimiter(): String;

    public function components(): array;

    public function EOFTerminated(): Bool;
}
