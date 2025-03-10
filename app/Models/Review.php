<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Ramsey\Uuid\Uuid;

class Review extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'reviews';
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $primaryKey = 'id';

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            // $model->uuid = Uuid::uuid4()->toString();
            if (empty($model->id)) {
                // $model->id = (string) Str::uuid();
                $model->id = Uuid::uuid4()->toString();
            }
        });
    }

    protected $fillable = [
        'id',
        'order_id',
        'user_id',
        'product_id',
        'rating',
        'review',
        'status'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

      public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
