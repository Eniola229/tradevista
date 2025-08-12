<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model
{
    protected $fillable = ['support_id', 'sender_id', 'sender_type', 'message'];

    public function support()
    {
        return $this->belongsTo(Support::class);
    }

    public function sender()
    {
        return $this->morphTo();
    }
}

