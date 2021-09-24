<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\Request;

class AcceptFriendshipsController extends Controller
{
    public function index()
    {
        //friendshipRequestReceived
        $friendshipRequests = Friendship::with('sender')->where([
            'recipient_id' => auth()->id()
        ])->get();
        return view('friendships.index', [
            'friendshipRequests' => \request()->user()->friendshipRequestReceived
        ]);
    }

    public function store(User $sender)
    {
        \request()->user()->acceptFriendRequestFrom($sender);

        return response()->json([
           'friendship_status' => 'accepted'
        ]);
    }

    public function destroy(User $sender)
    {
        \request()->user()->denyFriendRequestFrom($sender);

        return response()->json([
            'friendship_status' => 'denied'
        ]);
    }
}
