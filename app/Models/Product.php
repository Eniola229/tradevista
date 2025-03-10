<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Ramsey\Uuid\Uuid;
use Session;

class Product extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table = 'products';
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
        'category_id',
        'product_name',
        'product_code',
        'product_color',
        'product_price',
        'product_discount',
        'product_description',
        'stock',
        'product_weight',
        'product_video',
        'main_image',
        'description',
        'status'
    ];

    public function attributes()
    {
        return $this->hasMany(ProductsAttribute::class);
    }

    public function images()
    {
        return $this->hasMany(ProductsImage::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

      public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);  
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function getDiscountedPrice($product_id)
    {
        // Fetch product details
        $proDetails = Product::select('product_price', 'product_discount', 'category_id')
            ->where('id', $product_id)
            ->first();

        // Check if product exists
        if (!$proDetails) {
            return 0; // Return 0 if product not found
        }

        // Convert product details to array
        $proDetails = $proDetails->toArray();

        // Debugging logs
        \Log::info("Product Details: ", $proDetails);

        // Check if discount is available and valid
        if (!empty($proDetails['product_discount']) && $proDetails['product_discount'] > 0) {
            $discounted_price = $proDetails['product_discount']; // Return discount price directly
        } else {
            $discounted_price = $proDetails['product_price']; // No discount, return original price
        }

        // Debugging log
        \Log::info("Final Corrected Discounted Price for Product ID {$product_id}: {$discounted_price}");

        return $discounted_price; // Return numeric value
    }


    public static function checkStockLimit()
    {
        $cartItems = Cart::where('user_id', auth()->id())->get();

        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->stock_quantity) {
                return "The product {$item->product->product_name} is out of stock.";
            }
        }

        return null;
    }

}
