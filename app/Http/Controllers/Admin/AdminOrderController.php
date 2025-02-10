<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\User;
use App\Models\Setup;

use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
public function index()
{
    $orders = Order::with([
        'user', 
        'orderProducts.product', // ✅ Fixed relationship name
        'shippingAddress', 
        'orderProducts.product.seller' // ✅ Fixed relationship name
    ])
    ->whereNotNull('user_id')
    ->orderBy('created_at', 'desc')
    ->paginate(10);

    // Attach seller details from the setup table
    foreach ($orders as $order) {
        foreach ($order->orderProducts as $orderProduct) { // ✅ Fixed relationship name
            // Fetch seller details from the setup table using user_id
            $sellerInfo = DB::table('setups')->where('user_id', $orderProduct->product->user_id)->first();
            $orderProduct->product->seller_info = $sellerInfo; // Attach seller info dynamically
        }
    }

    return view('admin.orders', compact('orders'));
}

    public function show($id)
    {
        $order = Order::with([
            'user',
            'orderProducts.product',
            'shippingAddress',
            'orderProducts.product.seller'
        ])->findOrFail($id);

        // Attach seller details from the setup table
        foreach ($order->orderProducts as $orderProduct) {
            $sellerInfo = DB::table('setups')->where('user_id', $orderProduct->product->user_id)->first();
            $orderProduct->product->seller_info = $sellerInfo; // Attach seller info dynamically
        }

        return view('admin.order_details', compact('order'));
    }


    // Update order status (AJAX)
public function updateStatus(Request $request, $id)
{
    try {
        // Find order
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Update delivery status
        $order->delivery_status = $request->delivery_status;
        $order->save();

        return response()->json(['success' => 'Delivery status updated successfully!'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}
