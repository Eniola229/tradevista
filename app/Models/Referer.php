<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Ramsey\Uuid\Uuid;

class Referer extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'referers';
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
        'user_id',
        'referer_id'
    ];

    public function referer()
    {
        return $this->belongsTo(User::class, 'referer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
