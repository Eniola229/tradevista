<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Ramsey\Uuid\Uuid;

class ShippingAddress extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'shipping_addresses';
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
        'user_id',
        'country',
        'state',
        'town_city',
        'address',
        'zip',
        'address_type'
    ];
}
