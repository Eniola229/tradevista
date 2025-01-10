<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Ramsey\Uuid\Uuid;
use Session;

class Cart extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table = 'carts';
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
        'session_id',
        'order_code',
        'user_id',
        'product_id',
        'size',
        'quantity'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public static function getCart($sessionId = null, $customerId = null)
    {
        if ($customerId) {
            return self::where('user_id', $customerId)->with('product')->get();
        }

        if ($sessionId) {
            return self::where('session_id', $sessionId)->with('product')->get();
        }

        return null;
    }

    public static function clearCart($sessionId = null, $customerId = null)
    {
        if ($customerId) {
            return self::where('user_id', $customerId)->delete();
        } elseif ($sessionId) {
            return self::where('session_id', $sessionId)->delete();
        }

        return false;
    }

    public static function getCartItems($user)
    {
        $sessionId = Session::getId();
        $customerId = $user->id ?? null;

        $cartItems = Cart::when($customerId, function ($query) use ($customerId) {
            return $query->where('user_id', $customerId);
        }, function ($query) use ($sessionId) {
            return $query->where('session_id', $sessionId);
        })
            ->with('product')
            ->get()
            ->unique('product_id'); // Ensure no duplicates by product_id

        return $cartItems;
    }

}
