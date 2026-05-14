<?php

namespace App\DTOs;

class UpdateCategoryData
{
    public function __construct(
        public string $name,
    ) {}
}
