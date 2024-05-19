<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activeLawyers = $this->getActiveLawyers();
        $subscriptions = Subscription::paginate(10);

        return view('subscriptions.index', compact('subscriptions', 'activeLawyers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $activeLawyers = $this->getActiveLawyers();

        return view('subscriptions.create', compact('activeLawyers'));
    }

    private function getActiveLawyers()
    {
        $all_lawyers = User::whereHas('roles', function ($query) {
            $query->where('name', 'user');
        })->statusActive()->get();

        return $all_lawyers->mapWithKeys(function ($user) {
            return [$user->id => $user->full_name];
        })->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'subscription_type' => 'required',
            'start_date' => 'required|date',
        ]);
        $startDate = Carbon::parse($request->start_date);

        $request['end_date'] = $startDate->addMonths($request->subscription_duration);

        Subscription::create($request->all());

        return redirect()->route('subscriptions')->with('success', 'Subscription created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subscription = Subscription::findOrFail($id);

        $activeLawyers = $this->getActiveLawyers();

        return view('subscriptions.edit', compact('subscription', 'activeLawyers'));
    }

    // Display the specified resource.
    public function show($id)
    {
        $subscription = Subscription::findOrFail($id);

        $activeLawyers = $this->getActiveLawyers();

        return view('subscriptions.show', compact('subscription', 'activeLawyers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required',
            'subscription_type' => 'required',
            'start_date' => 'required|date'
        ]);

        $startDate = Carbon::parse($request->start_date);
        $end_date = $startDate->addMonths($request->subscription_duration);

        $subscription = Subscription::findOrFail($id);
        $subscription->user_id = $request->user_id;
        $subscription->subscription_type = $request->subscription_type;
        $subscription->subscription_duration = $request->subscription_duration;
        $subscription->start_date = $request->start_date;
        $subscription->end_date = $end_date;
        $subscription->save();

        return redirect()->route('subscriptions')->with('success', 'Subscription updated successfully.');
    }

    public function destroy($id)
    {
        // Find the user by ID
        $subscription = Subscription::findOrFail($id);

        // Set the deleted_by field with the authenticated subs$subscription's ID
        $subscription->deleted_by = Auth::id();
        $subscription->save(); // Save the subs$subscription to update the deleted_by field

        // Soft delete the subs$subscription
        $subscription->delete();

        return redirect()->route('subscriptions')->with('success', 'Subscription deleted successfully!');
    }
}
