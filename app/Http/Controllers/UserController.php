<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Course;
use App\Enrolment;
use App\Group;
use App\GroupAttendance;
use App\GroupMember;
use App\GroupSession;
use App\Instructor;
use App\InstructorCourse;
use App\Payment;
use App\Period;
use App\SubscriptionPlan;
use App\User;
use App\Timetable;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use App\Traits\UploadTrait;

class UserController extends Controller
{
    use UploadTrait;
    /**
     * Display a listing of the resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::where('api_token', '=', $request->bearerToken())->first();
        $role = $user->role;

        switch ($role) {
            case 'admin':
                return User::all();
            default:
                $users[] = $user;

                $groups = $user->groups;
                $group_chat_arrays = [];
                foreach ($groups as $group) {
                    $users[] = $group->creator;
                    $group_chat_arrays[] = $group->chats;
                }
                foreach ($group_chat_arrays as $group_chat_array) {
                    foreach ($group_chat_array as $group_chat) {
                        $users[] = $group_chat->sender;
                    }
                }

                return $users;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request `
     * @return \Illuminate\Http\Response
     */
    public function createUser(Request $request)
    {
        $phonenumber = request('phonenumber');

        $user = User::where('phonenumber', $phonenumber)->first();

        if (!$user) {
            $userid = Str::random(80);
            $user = User::forceCreate(
                [
                    'userid' => $userid,
                    'api_token' => Str::random(80),
                    'connected' => false,
                ] +
                $request->all());
        }
        return $user;
    }


    public function confirmRegistration(Request $request)
    {
        $user = User::where('phonenumber', request('phonenumber'))->first();
        $connected = $user->connected;
        $user->update([
            'connected' => false
        ]);

        if (true) {
//        if ($connected) {
            return $this->sendAll($user);
        } else {
            return response()->json(array(
                'connected' => 0,
            ));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     *
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $attributes = [];
        if($request->hasFile("picture")) {
            // Define folder path
            $folder = '/uploads/user-profile-pics/';// Get image file
            $image = $request->file("picture");// Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $user->userid . '.' . $image->getClientOriginalExtension();// Upload image
            $this->uploadOne($image, $folder, '', $user->userid);

            $attributes = $attributes + ['profilepicurl' => asset('storage/app') . "$filePath"];
        }

        $attributes = $attributes + [
                'name' => request('name')
            ];
        $context = request()->all();
//        Log::info('request', $context);
        $user->update(
            $attributes
        );

        $updated_user = User::where('userid', $user->userid)->first();

        return $updated_user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function fetchAll(Request $request)
    {
        $user = User::where('api_token', '=', $request->bearerToken())->first();
        $role = $user->role;

        switch ($role) {
            case 'admin':
                return $this->sendAll($user);
            default:
                return $this->sendAll($user);
                break;
        }
    }

    /**
     * @param $user
     * @param array $payment_arrays
     * @param array $users
     * @return \Illuminate\Http\JsonResponse
     */
    private function sendAll($user): \Illuminate\Http\JsonResponse
    {
        // fetch chats
        $groups = $user->groups;
        $chats = [];
        $group_chat_arrays = [];
        foreach ($groups as $group) {
            $group_chat_arrays[] = $group->chats;
        }
        foreach ($group_chat_arrays as $group_chat_array) {
            foreach ($group_chat_array as $group_chat) {
                $chats[] = $group_chat;
            }
        }
        $groups = $user->groups;
        $group_attendances = $user->groupAttendances;
        $group_members = GroupMember::where('memberid', $user->userid)->get();
        // fetch group sessions
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

        $payments = $user->payments;
        //fetch users
        $users[] = $user;

        $groups = $user->groups;
        $group_chat_arrays = [];
        foreach ($groups as $group) {
            $users[] = $group->creator;
            $group_chat_arrays[] = $group->chats;
        }
        foreach ($group_chat_arrays as $group_chat_array) {
            foreach ($group_chat_array as $group_chat) {
                $users[] = $group_chat->sender;
            }
        }

        $subscription_plan = SubscriptionPlan::all();

        return Response::json(array(
            'userid' => $user->userid,
            'api_token' => $user->api_token,
            'connected' => 1,

            'chats' => $chats,
            'groups' => $groups,
            'group_attendances' => $group_attendances,
            'group_members' => $group_members,
            'group_sessions' => $group_sessions,
            'payments' => $payments,
            'subscription_plan' => $subscription_plan,
            'users' => $users
        ));
    }
}
