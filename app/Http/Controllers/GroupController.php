<?php

namespace App\Http\Controllers;

use App\Group;
use App\GroupMember;
use App\SubmittedAssignment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use App\Traits\UploadTrait;

class GroupController extends Controller
{
    use UploadTrait;

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
                return Group::all();
            default:
                return $user->groups;
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = [];
        if ($request->hasFile("file")) {
            // Define folder path
            $folder = '/uploads/groups/';// Make a file name based on title and current timestamp
            $name = $request->input('groupid');// Get image file
            $image = $request->file("file");// Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();// Upload image
            $this->uploadOne($image, $folder, 'public', $name);

            $attributes = $attributes + ['profilepicurl' => config('app.url') . "$filePath"];
        }

        $attributes = $attributes + [
                'groupid' => $request->input('groupid'),
                'creatorid' => $request->input('creatorid'),
                'title' => $request->input('title')
            ];
        $group = Group::forceCreate($attributes);
        $user = User::where('api_token', '=', $request->bearerToken())->first();
        list($user, $group_member) = $this->addMemberToGroup($user->phonenumber, $group);
        return Response::json(array(
            'group_member' => $group_member,
            'group' => $group
        ));
    }

    public function addGroupMembers(Request $request)
    {
        $users = [];
        $group_members = [];

        $groupid = request('groupid');
        $group = Group::find($groupid);

        if (!$group) {
            $attributes = [
                'groupid' => $groupid,
                'creatorid' => $user = User::where('api_token', '=', $request->bearerToken())->first()->userid,
                'title' => request('title'),
                'description' => request('description')
            ];

            if ($request->hasFile("group_image")) {// Get image file
                $folder = '/uploads/groups/';
                $name = $groupid;
                $image = $request->file("group_image");// Make a file path where image will be stored [ folder path + file name + file extension]
                $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();// Upload image
                $this->uploadOne($image, $folder, 'public', $name);/**/
                $attributes = $attributes + ['profilepicurl' => config('app.url') . "$filePath"];
            }
            $group = Group::forceCreate(
                $attributes
            );
        }

        $i = 0;
        while ($request->has("phonenumber" . $i)) {

            $phonenumber = request('phonenumber' . $i);
            list($user, $group_member) = $this->addMemberToGroup($phonenumber, $group);
            $users[] = $user;
            $group_members[] = $group_member;
            $i++;
        }
        return Response::json(array(
            'users' => $users,
            'group_members' => $group_members
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Group $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        return $group;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Group $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Group $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        $attributes = [];
        if($request->hasFile("profilepicurl")) {
            // Define folder path
            $folder = '/uploads/group-pics/';// Get image file
            $image = $request->file("profilepicurl");// Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $group->groupid . '.' . $image->getClientOriginalExtension();// Upload image
            $this->uploadOne($image, $folder, '', $group->groupid);

            $attributes = $attributes + ['profilepicurl' => asset('storage/app') . "$filePath"];
        }

        $attributes = $attributes + [
                'title' => request('title'),
                'description' => request('description')
            ];
        $group->update(
            $attributes
        );


        $updated_group = Group::where('groupid', $group->groupid)->first();

        return $updated_group;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Group $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $group->delete();
        return Response::json(array(
            'successfully_deleted' => true
        ));
    }

    /**
     * @param $phonenumber
     * @param $group
     * @return array
     */
    public function addMemberToGroup($phonenumber, $group): array
    {
        $user = User::where('phonenumber', $phonenumber)->first();
        $group_member = null;
        if (!$user) {
            $user = User::forceCreate(
                [
                    'userid' => Str::random(80),
                    'api_token' => Str::random(80),
                    'phonenumber' => $phonenumber
                ]);
            $group_member = GroupMember::forceCreate(
                [
                    'groupmemberid' => Str::random(80),
                    'groupid' => $group->groupid,
                    'memberid' => $user->userid
                ]);
        } else {
            $group_member = GroupMember::where('memberid', $user->userid)
                ->where('groupid', $group->groupid)
                ->first();
            if (!$group_member) {
                $group_member = GroupMember::forceCreate(
                    [
                        'groupmemberid' => Str::random(80),
                        'groupid' => $group->groupid,
                        'memberid' => $user->userid
                    ]);
            }
        }
        return array($user, $group_member);
    }
}
