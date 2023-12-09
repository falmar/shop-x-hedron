<?php

namespace App\Libraries;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Ramsey\Uuid\Uuid;

trait HasUuidTrait
{
    use HasUuids;

    public function newUniqueId(): string
    {
        return Uuid::uuid7()->toString();
    }
}
