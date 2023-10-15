<?php

namespace App\Http\Controllers;

use App\Session;
use App\Enrolment;
use App\GroupSession;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GroupSessionController extends Controller
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
                return GroupSession::all();
            default:
                $groups = $user->groups;
                $group_sessions = [];
                $group_sessions_arrays = [];
                foreach ($groups as $group) {
                    $group_sessions_arrays[] = $group->groupSessions;
                }
                foreach ($group_sessions_arrays as $group_sessions_array) {
                    foreach ($group_sessions_array as $group_session) {
                        $group_sessions[] = $group_session;
                    }
                }
                return $group_sessions;
        }
    }

    public function groupDocs(Request $request)
    {
//        DB::table('group_sessions')->distinct()->get(['docurl']);
        return GroupSession::distinct()->whereNotNull('docurl')->get(['docurl']);
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
        $groupsessionid = Str::random(80);
        GroupSession::forceCreate(
            ['groupsessionid' => $groupsessionid] +
            $request->all());

        $groupsession = GroupSession::where('groupsessionid', $groupsessionid)->first();

        return response()->json($groupsession);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GroupSession  $groupsession
     * @return \Illuminate\Http\Response
     */
    public function show($groupsessionid)
    {
        $groupsession = GroupSession::where('groupsessionid', $groupsessionid)->first();

        return response()->json($groupsession);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GroupSession  $groupsession
     * @return \Illuminate\Http\Response
     */
    public function edit(GroupSession $groupsession)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GroupSession  $groupsession
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GroupSession $groupsession)
    {
        $groupsession->update($request->all());

        $updated_groupsession = GroupSession::where('groupsessionid', $groupsession->groupsessionid)->first();

        return response()->json($updated_groupsession);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GroupSession  $groupsession
     * @return \Illuminate\Http\Response
     */
    public function destroy(GroupSession $groupsession)
    {
        //
    }
}
