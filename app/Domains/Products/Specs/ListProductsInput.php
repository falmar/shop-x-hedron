<?php

namespace App\Domains\Products\Specs;

class ListProductsInput
{
    public ?string $search = null;

    /** @var string[]  */
    public array $ids = [];
}
