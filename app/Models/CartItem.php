<?php

namespace App\Models;

use App\Libraries\HasUuidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $session_id
 * @property string $email
 * @property \DateTimeInterface|null $created_at
 * @property \DateTimeInterface|null $updated_at
 * @property \DateTimeInterface|null $deleted_at
 */
class CartItem extends Model
{
    use SoftDeletes;
    use HasUuidTrait;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s.u',
        'updated_at' => 'datetime:Y-m-d H:i:s.u',
        'deleted_at' => 'datetime:Y-m-d H:i:s.u'
    ];
    protected $dateFormat = 'Y-m-d H:i:s.u';

    /**
     * @return string[]
     */
    public function getDates(): array
    {
        return ['created_at', 'updated_at', 'deleted_at'];
    }
}
