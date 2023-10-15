<?php

namespace App\Http\Controllers;

use App\Plan;
use App\Enrolment;
use App\SubscriptionPlan;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubscriptionPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::where('api_token', '=', $request->bearerToken())->first();
        $role = $user->role;

        switch ($role) {
            case 'admin':
                return SubscriptionPlan::all();
            default:

                'default';
                return SubscriptionPlan::all();
        }
    }

    public function subscriptionDocs(Request $request)
    {
//        DB::table('subscription_plans')->distinct()->get(['docurl']);
        return SubscriptionPlan::distinct()->whereNotNull('docurl')->get(['docurl']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $subscriptionplanid = Str::random(80);
        SubscriptionPlan::forceCreate(
            ['subscriptionplanid' => $subscriptionplanid] +
            $request->all());

        $subscriptionplan = SubscriptionPlan::where('subscriptionplanid', $subscriptionplanid)->first();

        return response()->json($subscriptionplan);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SubscriptionPlan  $subscriptionplan
     * @return \Illuminate\Http\Response
     */
    public function show($subscriptionplanid)
    {
        $subscriptionplan = SubscriptionPlan::where('subscriptionplanid', $subscriptionplanid)->first();

        return response()->json($subscriptionplan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SubscriptionPlan  $subscriptionplan
     * @return \Illuminate\Http\Response
     */
    public function edit(SubscriptionPlan $subscriptionplan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SubscriptionPlan  $subscriptionplan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubscriptionPlan $subscriptionplan)
    {
        $subscriptionplan->update($request->all());

        $updated_subscriptionplan = SubscriptionPlan::where('subscriptionplanid', $subscriptionplan->subscriptionplanid)->first();

        return response()->json($updated_subscriptionplan);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SubscriptionPlan  $subscriptionplan
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubscriptionPlan $subscriptionplan)
    {
        //
    }
}
