<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\Admin;
use Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use App\Models\Product;
use App\Models\OrderProduct;
use App\Models\Order;
use App\Models\Waitlist;
use App\Models\Withdraw;
use Illuminate\Support\Facades\DB;


class AdminAuthController extends Controller
{
    /**
     * Show the login page.
     *
     * @return View
     */
    public function index(): View
    {
        return view('admin.login');
    }  

    /**
     * Show the registration page.
     *
     * @return View
     */
    public function registration(): View
    {
        return view('admin.registration');
    }

    /**
     * Handle login request.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function postLogin(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        
        // Use the 'admins' guard for authentication
        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->intended('admin/dashboard')
                        ->withSuccess('You have successfully logged in');
        }

        return redirect("admin/login")->with('error', 'Oops! You have entered invalid credentials');
    }

    /**
     * Handle registration request.
     *
     * @param Request $request
     * @return RedirectResponse
     */
        public function postRegistration(Request $request): RedirectResponse
        {  
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:admins',
                'mobile' => 'required|numeric|unique:admins',
                'password' => 'required|min:6',
            ]);

            // Hash the password before saving to the database
            $data = $request->all();
            $data['password'] = bcrypt($data['password']);

            // Use updateOrCreate to either update or create a new admin record
            $admin = Admin::updateOrCreate(
                [
                    'email' => $data['email'], // Search by email
                ],
                [
                    'name' => $data['name'],
                    'mobile' => $data['mobile'],
                    'password' => $data['password'],
                ]
            );

            return redirect()->back()->with('message', 'Great! The admin record has been successfully updated or created.');
        }
    /**
     * Show the admin dashboard.
     *
     * @return View|RedirectResponse
     */
    public function dashboard()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        $productCount = Product::all()->count();
        $withdrawCount = Withdraw::where('status', 'PENDING')->get()->count();
        $pendingProductCount = Product::where('status', 'INACTIVE')->get()->count();
        $userCount = User::all()->count();
        $totalBalance = User::all()->sum('balance');

        return view('admin.dashboard', compact("users", "productCount", "userCount", "totalBalance", 'pendingProductCount', 'withdrawCount'));
    }

    public function generateAdminSalesReport()
    {
        $totalOrders = Order::count();

        // Total revenue
        $totalRevenue = \App\Models\OrderProduct::sum(\DB::raw('product_qty * product_price'));

        // Average order value
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Refunds (if payment_status column is used to track them)
        $refunds = Order::where('payment_status', 'refunded')
            ->with('orderProducts')
            ->get()
            ->sum(function ($order) {
                return $order->orderProducts->sum(function ($op) {
                    return $op->product_qty * $op->product_price;
                });
            });

        return response()->json([
            'total_revenue'        => $totalRevenue,
            'total_orders'         => $totalOrders,
            'average_order_value'  => $averageOrderValue,
            'refunds'              => $refunds,
        ]);
    }


    /**
     * Create a new admin instance.
     *
     * @param array $data
     * @return Admin
     */
    public function create(array $data)
    {
        return Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Handle admin logout.
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        Session::flush();
        Auth::guard('admin')->logout();

        return redirect('admin/login');
    }
}
