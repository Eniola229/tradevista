<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Ramsey\Uuid\Uuid;

class Withdraw extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'withdrawals';
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

    protected $fillable = ['user_id', 'amount', 'status', 'receipt', 'receipt_id', 'account_number', 'bank_name', 'account_name', 'note'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
