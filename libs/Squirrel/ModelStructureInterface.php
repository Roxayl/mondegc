<?php

namespace Squirrel;

interface ModelStructureInterface
{
    public function __construct(int|string|array|null $data = null);

    public function __get(string $prop): mixed;

    public function __set(string $prop, mixed $value): void;
}
