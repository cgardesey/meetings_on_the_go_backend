<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Instructor;
use App\Student;
use App\Traits\UploadTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;


class ChatController extends Controller
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
                return Chat::all();
            default:
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
                return $chats;
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $chat = Chat::find(request('chatid'));
        if (!$chat) {
            $attributes = [
                'chatid' => request('chatid'),
                'chatrefid' => request('chatrefid'),
                'groupid' => request('groupid'),
                'senderid' => request('senderid')
            ];
            if ($request->has('file')) {

                // Get image file
                $image = $request->file('file');// Make a file name based on attachmenttitle and current timestamp
                $title = $request->input('chatid');// Define folder path
                $folder = '/uploads/chats/';// Make a file path where image will be stored [ folder path + file name + file extension]
                $filePath = $folder . $title . '.' . $image->getClientOriginalExtension();// Upload image
                $this->uploadOne($image, $folder, 'public', $title);
                $attributes = $attributes +
                    [
                        'attachmenttitle' => request('attachmenttitle'),
                        'attachmenturl' => config('app.url') . "$filePath"
                    ];
            } else {
                if ($request->has('attachmenturl')) {
                    $attributes = $attributes +
                        [
                            'attachmenturl' => request('attachmenturl'),
                        ];
                }
                $attributes = $attributes +
                    [
                        'text' => request('text'),
                        'link' => request('link'),
                        'linktitle' => request('linktitle'),
                        'linkdescription' => request('linkdescription'),
                        'linkimage' => request('linkimage')
                    ];
            }
            $chat = Chat::forceCreate($attributes);
            $sender = User::find(request('senderid'));
            if ($request->has('chatrefid')) {
                $referenced_chat = Chat::find(request('chatrefid'));
                return Response::json(array(
                    'chat' => $chat,
                    'sender' => $sender,
                    'referenced_chat' => $referenced_chat
                ));
            } else {
                return Response::json(array(
                    'chat' => $chat,
                    'sender' => $sender
                ));
            }
        } else {
            return Response::json(array(
                'already_exists' => true,
                'chat' => $chat
            ));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Chat $chat
     * @return \Illuminate\Http\Response
     */
    public function show(Chat $chat)
    {
        return $chat;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Chat $chat
     * @return \Illuminate\Http\Response
     */
    public function edit(Chat $chat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Chat $chat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chat $chat)
    {
        $chat->update($request->all());

        $updated_chat = Chat::where('chatid', $chat->chatid)->first();

        return response()->json($updated_chat);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Chat $chat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chat $chat)
    {
        //
    }
}
