<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPassMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $req){
        $user = User::where("email", $req->input("email"));
        if (!$user) {
            return response()->json("Please give correct user info");
        }

        if (!Hash::check($req->input("password"), $user->password)) {
            return response()->json("wrong password");
        }

        return response()->json(["isAuth"=>true, "user"=>$user]);
    }

    public function register(Request $req){
        $user = User::where("email", $req->input("email"));
        if ($user) {
            return response()->json("Please give unsaved email");
        }

        $newUser = new User();
        $newUser->name = $req->input("name");
        $newUser->email = $req->input("email");
        $newUser->password = Hash::make($req->input("password"));
        $newUser->save();

        return response()->json($newUser);
    }


    public function getStories(Request $req){
        $user = User::find($req->query("userid"));
        if (!$user) {
            return response()->json("Please give correct user info");
        }

        $storiesArray = json_decode($user->stories, true);
        $notLateStories = array();
        for ($i=0; $i < count($storiesArray); $i++) { 
            $diff = Carbon::parse($storiesArray[$i]["createdTime"])->diff(Carbon::now());
            if($diff->days == 0){
                array_push($notLateStories, $storiesArray[$i]);
            }
        }

        return response()->json($notLateStories);
    }

    public function addStories(Request $req){
        $user = User::find($req->query("userid"));
        if (!$user) {
            return response()->json("Please give correct user info");
        }

        if ($req->hasFile("storyPost")) {
            $storiesArray = json_decode($user->stories, true);
            $storyPostFile = $req->file("storyPost");
            $storyPostFileName = time()."_".$req->query("userid")."-stories-".$storyPostFile->getClientOriginalName();
            $storyPostFile->move(public_path("stories"), $storyPostFileName);
            $storyPostText = url("stories", $storyPostFileName);

            array_push($storiesArray, ["storyUrl"=>$storyPostText, "createdTime"=>Carbon::now()]);
            $user->stories = json_encode($storiesArray);
            $user->save();

        } else{
            return response()->json("please give story post");
        }

        return response()->json($user);
    }   

    public function forgotPassword(Request $req){
        $user = User::where("email", $req->input("email"))->get()[0];
        if(!$user){
            return response()->json("Please give correct user mail");
        }
        $token = Str::random(32);
        $user->remember_token = $token;
        $user->save();

        Mail::to($user->email)->send(new ForgotPassMail($token));

        return response()->json($user ? true : false);
    }   

    public function newPassword(Request $req){
        $user = User::where("remember_token", $req->query("token"))->get()[0];
        if(!$user){
            return response()->json("Please give valid token");
        }

        $user->remember_token = null;
        $user->password = $req->input("password");
        $user->save();

        return response()->json($user);
    }
}
