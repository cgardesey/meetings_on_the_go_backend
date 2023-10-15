<?php

namespace App\Http\Controllers;

use App\Member;
use App\Enrolment;
use App\GroupMember;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class GroupMemberController extends Controller
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
                return GroupMember::all();
            default:

                'default';
                return GroupMember::where('memberid', $user->userid)->get();
        }
    }

    public function students(Request $request)
    {
        $user = User::where('api_token', '=', $request->bearerToken())->first();
        $role = $user->role;

        switch ($role) {
            case 'admin':

            case 'group':

            case 'student':
                return GroupMember::find(request('groupmemberid'))->students;

            default:
                'default';
                break;
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
        $groupmemberid = Str::random(80);
        GroupMember::forceCreate(
            ['groupmemberid' => $groupmemberid] +
            $request->all());

        $groupmember = GroupMember::where('groupmemberid', $groupmemberid)->first();

        return response()->json($groupmember);
    }

    public function all(Request $request)
    {
        // Define folder path
        $folder = '/uploads/group-members/';
        // Make a file name based on title and current timestamp
        $name = $request->input('groupmemberid');

        $i = 0;

        while($request->has("groupmemberid" . $i)) {
            if (true) { // Get image file
                $image = $request->file("file$i");// Make a file path where image will be stored [ folder path + file name + file extension]
                $filePath = $folder . $name . $i . '.' . $image->getClientOriginalExtension();// Upload image
                $this->uploadOne($image, $folder, 'public', $name . $i);/**/
            }

            SubmittedAssignment::forceCreate(
                ['submittedassignmentid'
                => $request->input('submittedassignmentid')] +
                ['title' => $request->input('title')] +
                ['url' => config('app.url') . "$filePath"] +
                ['assignmentid' => $request->input('assignmentid')] +
                ['studentid' => $request->input('studentid')]
            );
            $i++;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GroupMember  $groupmember
     * @return \Illuminate\Http\Response
     */
    public function show($groupmemberid)
    {
        $groupmember = GroupMember::where('groupmemberid', $groupmemberid)->first();

        return response()->json($groupmember);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GroupMember  $groupmember
     * @return \Illuminate\Http\Response
     */
    public function edit(GroupMember $groupmember)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GroupMember  $groupmember
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GroupMember $groupmember)
    {
        $groupmember->update($request->all());

        $updated_groupmember = GroupMember::where('groupmemberid', $groupmember->groupmemberid)->first();

        return response()->json($updated_groupmember);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GroupMember  $groupmember
     * @return \Illuminate\Http\Response
     */
    public function destroy(GroupMember $groupmember)
    {
        //
    }

    public function bulkDestroy(Request $request)
        {
        $i = 0;
        while ($request->has('userid' . $i)) {
            $userid = request('userid' . $i);
            $result = DB::table('users')->where('userid', $userid)->delete();
//            DB::table('group_members')->where('memberid', $userid)->delete();

            Log::info('result', ['result' => $result]);
            $i++;
        }
        return Response::json(array(
            'successfully_deleted' => true
        ));
    }
}
