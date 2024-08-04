<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Rent;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Vakalatnama;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $dashboardData = [];
        if ($user->hasRole('president')) {
            // Retrieve the start and end date from the request
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');

            // Total users
            $userQuery = User::query();
            // Apply date filters if provided
            if ($startDate) {
                $userQuery->where('created_at', '>=', Carbon::parse($startDate)->startOfDay());
            }
            if ($endDate) {
                $userQuery->where('created_at', '<=', Carbon::parse($endDate)->endOfDay());
            }
            // Get the total count of users
            $totalUsers = $userQuery->count();
            // Total vendors
            $vendorRoles = ['vendor']; // Add vendor roles
            // Query to get the vendor user count
            $vendorQuery = User::whereHas('roles', function ($query) use ($vendorRoles) {
                $query->whereIn('name', $vendorRoles);
            });
            // Apply date filters to vendor query if provided
            if ($startDate) {
                $vendorQuery->where('created_at', '>=', Carbon::parse($startDate)->startOfDay());
            }
            if ($endDate) {
                $vendorQuery->where('created_at', '<=', Carbon::parse($endDate)->endOfDay());
            }
            // Get the count of vendor users
            $vendorUserCount = $vendorQuery->count();
            // Calculate lawyer user count
            $lawyerUserCount = $totalUsers - $vendorUserCount;

            // calculate lawyers
            $dashboardData['total_users'] = $totalUsers;
            $dashboardData['total_lawyers'] = $lawyerUserCount;
            $dashboardData['total_vendors'] = $vendorUserCount;

            // Payments
            $paymentQuery = Payment::query();
            // Apply date filters to payment query if provided
            if ($startDate) {
                $paymentQuery->where('payment_date', '>=', Carbon::parse($startDate)->startOfDay());
            }
            if ($endDate) {
                $paymentQuery->where('payment_date', '<=', Carbon::parse($endDate)->endOfDay());
            }
            // Calculate the total payment amount for all users
            $totalPaymentAmount = $paymentQuery->sum('payment_amount');
            $dashboardData['total_subscriptions_received'] = $totalPaymentAmount;

            // Rent
            $rentQuery = Rent::query();
            // Apply date filters to payment query if provided
            if ($startDate) {
                $rentQuery->where('renewal_date', '>=', Carbon::parse($startDate)->startOfDay());
            }
            if ($endDate) {
                $rentQuery->where('renewal_date', '<=', Carbon::parse($endDate)->endOfDay());
            }
            // Calculate the total payment amount for all users
            $totalRentAmount = $rentQuery->sum('rent_amount');
            $dashboardData['total_rent_received'] = $totalRentAmount;

            // Spent on Vouchers
            $voucherQuery = Voucher::query();
            // Apply date filters to payment query if provided
            if ($startDate) {
                $voucherQuery->where('date', '>=', Carbon::parse($startDate)->startOfDay());
            }
            if ($endDate) {
                $voucherQuery->where('date', '<=', Carbon::parse($endDate)->endOfDay());
            }
            $totalVoucherAmount = $voucherQuery->sum('price');

            $dashboardData['total_voucher_spent'] = $totalVoucherAmount;

            // pending rent
            $next1Day = Carbon::now()->addDays(1);

            $subQuery = Rent::select('user_id', DB::raw('MAX(end_date) as latest_end_date'))
                ->groupBy('user_id');

            $rentsQuery = Rent::joinSub($subQuery, 'latest_rents', function ($join) use ($next1Day) {
                $join->on('rents.user_id', '=', 'latest_rents.user_id')
                    ->on('rents.end_date', '=', 'latest_rents.latest_end_date')
                    ->where('latest_rents.latest_end_date', '<', $next1Day);
            });

            if ($startDate) {
                $rentsQuery->where('rents.end_date', '>=', Carbon::parse($startDate)->startOfDay());
            }
            if ($endDate) {
                $rentsQuery->where('rents.end_date', '<=', Carbon::parse($endDate)->endOfDay());
            }

            $dashboardData['pending_rent'] = $rentsQuery->count();

            // pending subscription
            $latestSubscriptions = Subscription::select('user_id', DB::raw('MAX(end_date) as latest_end_date'))
                ->groupBy('user_id');

            $subscriptionsQuery = Subscription::joinSub($latestSubscriptions, 'latest', function ($join) use ($next1Day) {
                $join->on('subscriptions.user_id', '=', 'latest.user_id')
                    ->on('subscriptions.end_date', '=', 'latest.latest_end_date')
                    ->where('latest.latest_end_date', '<', $next1Day);
            });

            if ($startDate) {
                $subscriptionsQuery->where('subscriptions.end_date', '>=', Carbon::parse($startDate)->startOfDay());
            }
            if ($endDate) {
                $subscriptionsQuery->where('subscriptions.end_date', '<=', Carbon::parse($endDate)->endOfDay());
            }

            $dashboardData['pending_subscription'] = $subscriptionsQuery->count();

            // vakalatnama
            $vakalatnamaQuery = Vakalatnama::query();
            // Apply date filters to vakalatnama query if provided
            if ($startDate) {
                $vakalatnamaQuery->where('created_at', '>=', Carbon::parse($startDate)->startOfDay());
            }
            if ($endDate) {
                $vakalatnamaQuery->where('created_at', '<=', Carbon::parse($endDate)->endOfDay());
            }
            // Get the count of vendor users
            $dashboardData['vakalatnamas_count'] = $vakalatnamaQuery->count();
        }

        return view('home', compact('user', 'dashboardData'));
    }
}
