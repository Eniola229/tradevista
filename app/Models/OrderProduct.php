<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Notifications\Notifiable;

class OrderProduct extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table = 'order_products';
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
        'order_id',
        'product_id',
        'product_price',
        'product_code',
        'product_qty',
        'product_total',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
 
}
