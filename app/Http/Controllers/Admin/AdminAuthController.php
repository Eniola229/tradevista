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

    // Sales Report JSON API
    public function generateAdminSalesReport(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');

        // Base query for date filtering
        $filterQuery = Order::query();
        if ($from && $to) {
            $filterQuery->whereBetween('created_at', [$from, $to]);
        }

        // Daily grouped sales
        $daily = (clone $filterQuery)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as value')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'), 'ASC')
            ->get();

        // KPI values (separate query to avoid groupBy issues)
        $totalRevenue = $filterQuery->sum('total');
        $totalOrders = $filterQuery->count();
        $aov = $totalOrders > 0 ? ($totalRevenue / $totalOrders) : 0;

        return response()->json([
            'kpi' => [
                'total_revenue' => $totalRevenue,
                'total_orders'  => $totalOrders,
                'aov'           => $aov,
            ],
            'daily' => $daily
        ]);
    }

    // Download Sales Report as CSV
    public function downloadSalesReport(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');

        // Base query for date filtering
        $filterQuery = Order::query();
        if ($from && $to) {
            $filterQuery->whereBetween('created_at', [$from, $to]);
        }

        // Retrieve orders
        $orders = (clone $filterQuery)
            ->select('id', 'created_at', 'total')
            ->orderBy('created_at', 'ASC')
            ->get();

        // Prepare CSV content
        $csv = "Order ID,Date,Total\n";
        foreach ($orders as $order) {
            $csv .= "{$order->id},{$order->created_at},{$order->total}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename=\"sales_report.csv\"');
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
