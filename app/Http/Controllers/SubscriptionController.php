<?php

namespace App\Http\Controllers;

use App\Models\ModificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;
use App\Services\LawyerService;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    protected $lawyerService;

    public function __construct(LawyerService $lawyerService)
    {
        $this->lawyerService = $lawyerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activeLawyers = $this->lawyerService->getActiveLawyers(false);

        $subscriptionsQuery = Subscription::query();

        if (auth()->user()->hasRole('lawyer') || auth()->user()->hasRole('librarian')) {
            $subscriptionsQuery->where('user_id', auth()->user()->id);
        }

        $subscriptions = $subscriptionsQuery->orderBy('created_at', 'desc')->paginate(10);

        return view('subscriptions.index', compact('subscriptions', 'activeLawyers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $activeLawyers = $this->lawyerService->getActiveLawyers();

        return view('subscriptions.create', compact('activeLawyers'));
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
            'end_date' => 'required|date',
        ]);

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

        $activeLawyers = $this->lawyerService->getActiveLawyers();

        return view('subscriptions.edit', compact('subscription', 'activeLawyers'));
    }

    // Display the specified resource.
    public function show($id)
    {
        $subscription = Subscription::findOrFail($id);

        $activeLawyers = $this->lawyerService->getActiveLawyers();

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
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ]);

        $subscription = Subscription::findOrFail($id);

        if ($subscription) {
            if (auth()->user()->hasRole('president')) {

                $subscription->user_id = $request->user_id;
                $subscription->subscription_type = $request->subscription_type;
                $subscription->start_date = $request->start_date;
                $subscription->end_date = $request->end_date;
                $subscription->save();

                return redirect()->route('subscriptions')->with('success', 'Subscription updated successfully.');
            } else {

                $changes = $request->except(['_token', '_method']);
                $this->submitChangeRequest([
                    "table_name" => 'subscriptions',
                    "record_id" => $subscription->id,
                    "changes" => $changes,
                    "action" => ModificationRequest::REQUEST_TYPE_UPDATE,
                    "requested_by" => Auth::id(),
                ]);

                return redirect()->route('subscriptions')->with('success', 'Subscription updated request submitted successfully.');
            }
        }

        return redirect()->route('subscriptions')->with('error', 'Something went wrong.');
    }

    public function destroy($id)
    {
        // Find the user by ID
        $subscription = Subscription::findOrFail($id);

        if ($subscription) {
            if (auth()->user()->hasRole('president')) {
                // Set the deleted_by field with the authenticated subs$subscription's ID
                $subscription->deleted_by = Auth::id();
                $subscription->save(); // Save the subs$subscription to update the deleted_by field

                // Soft delete the subs$subscription
                $subscription->delete();

                return redirect()->route('subscriptions')->with('success', 'Subscription deleted successfully!');
            } else {
                $this->submitChangeRequest([
                    "table_name" => 'subscriptions',
                    "record_id" => $subscription->id,
                    "action" => ModificationRequest::REQUEST_TYPE_DELETE,
                    "requested_by" => Auth::id(),
                ]);

                return redirect()->route('subscriptions')->with('success', 'Subscription updated request submitted successfully.');
            }
        }

        return redirect()->route('subscriptions')->with('error', 'Something went wrong.');
    }
}
