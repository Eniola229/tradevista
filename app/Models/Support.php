<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Notifications\Notifiable;

class Support extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table = 'supports';
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
        'attendant_id',
        'ticket_id',
        'status',
        'problem_type',
        'message',
        'image_url',
        'image_id',
        'answer'
    ];

    public function messages()
    {
        return $this->hasMany(SupportMessage::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attendant()
    {
        return $this->belongsTo(Admin::class, 'attendant_id');
    }
}
