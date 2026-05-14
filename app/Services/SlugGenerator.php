<?php

namespace App\Services;

use Illuminate\Support\Str;

class SlugGenerator
{
    /**
     * @param  callable(string): bool  $exists
     */
    public function generateUnique(string $name, callable $exists, string $fallback = 'item'): string
    {
        $base = Str::slug($name);
        $base = $base === '' ? $fallback : $base;
        $slug = $base;
        $suffix = 2;

        while ($exists($slug)) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
