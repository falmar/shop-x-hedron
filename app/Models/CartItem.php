<?php

namespace App\Models;

use App\Libraries\HasUuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @class \App\Models\CartItem
 * @property string $id
 * @property string $cart_id
 * @property string $product_id
 * @property int $quantity
 * @property int $price
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
class CartItem extends Model
{
    use SoftDeletes;
    use HasUuidTrait;

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price'
    ];

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
