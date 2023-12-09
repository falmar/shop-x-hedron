<?php

namespace App\Models;

use App\Libraries\HasUuidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $brand
 * @property string $name
 * @property string $image_url
 * @property int $price
 * @property int $stock
 * @property int $review_count
 * @property int $review_rating
 * @property \DateTimeInterface|null $created_at
 * @property \DateTimeInterface|null $updated_at
 * @property \DateTimeInterface|null $deleted_at
 */
class Product extends Model
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
