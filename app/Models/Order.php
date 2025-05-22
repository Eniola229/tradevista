<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Notifications\Notifiable;

class Order extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table = 'orders';
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $primaryKey = 'id';

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            // $model->id = Uuid::uuid4()->toString();
            if (empty($model->id)) {
                // $model->id = (string) Str::uuid();
                $model->id = Uuid::uuid4()->toString();
            }
        });
        
    }

     protected $fillable = [
        'user_id',
        'transaction_id',
        'payment_status',
        'delivery_status',
        'shipping_address',
        'total_weight',
        'subtotal',
        'total',
        'order_note',
        'shipping_charges',
        'courier_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'order_id')->with('product');
    }

   public function shippingAddress()
    {
        return $this->belongsTo(ShippingAddress::class, 'shipping_address_id');
    }

    public function orders_products()
    {
        return $this->hasMany(OrderProduct::class,);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products')->withPivot('product_qty', 'product_price');
    }
}
