<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getNotifications($userid){
        $user = User::find($userid);
        if(!$user){return response()->json("User Not found");}

        return $user->notifications;
    }
}
