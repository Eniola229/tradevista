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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders_products()
    {
        return $this->hasMany(OrderProduct::class);
    }
}