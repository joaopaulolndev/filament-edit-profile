<?php

namespace Joaopaulolndev\FilamentEditProfile\Concerns;

trait HasSort
{
    public static function getSort(): int
    {
        return static::$sort;
    }

    public static function setSort(int $sort): void
    {
        static::$sort = $sort;
    }
}
