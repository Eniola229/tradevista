<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Notifications\Notifiable;

class ProductsImage extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table = 'products_images';
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
        'product_id',
        'image',
        'image_id',
        'image_url',
        'is_cover',
        'status',
    ];
}
