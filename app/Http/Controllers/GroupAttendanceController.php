<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\Enrolment;
use App\GroupAttendance;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GroupAttendanceController extends Controller
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
                return GroupAttendance::all();
            default:
                return $user->groupAttendances;
        }
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
        $groupattendanceid = Str::random(80);
        GroupAttendance::forceCreate(
            ['groupattendanceid' => $groupattendanceid] +
            $request->all());

        $groupattendance = GroupAttendance::where('groupattendanceid', $groupattendanceid)->first();

        return response()->json($groupattendance);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GroupAttendance  $groupattendance
     * @return \Illuminate\Http\Response
     */
    public function show($groupattendanceid)
    {
        $groupattendance = GroupAttendance::where('groupattendanceid', $groupattendanceid)->first();

        return response()->json($groupattendance);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GroupAttendance  $groupattendance
     * @return \Illuminate\Http\Response
     */
    public function edit(GroupAttendance $groupattendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GroupAttendance  $groupattendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GroupAttendance $groupattendance)
    {
        $groupattendance->update($request->all());

        $updated_groupattendance = GroupAttendance::where('groupattendanceid', $groupattendance->groupattendanceid)->first();

        return response()->json($updated_groupattendance);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GroupAttendance  $groupattendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(GroupAttendance $groupattendance)
    {
        //
    }
}
