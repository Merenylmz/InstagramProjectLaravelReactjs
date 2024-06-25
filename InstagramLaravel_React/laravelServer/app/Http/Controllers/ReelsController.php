<?php

namespace App\Http\Controllers;

use App\Models\Reels;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReelsController extends Controller
{
    public function getReelsByOffset(Request $req){
        $reels = DB::table("reels")->offset($req->query("offset"))->limit(1)->get();
        if (!$reels) {
            return response()->json(null);
        }
        return response()->json($reels[0]);
    }

    public function addReels(Request $req){
        $user = User::find($req->query("userid"));
        if (!$user) {
            return response()->json("Please give correct user id");
        }

        $newReels = new Reels();
        $newReels->title = $req->input("title");
        $newReels->description = $req->input("description");
        if ($req->hasFile("reelsUrl")) {
            $reelsFile = $req->file("reelsUrl");
            $reelsFileName = time()."_".$user->id."-reels-".$reelsFile->getClientOriginalName();
            $reelsFile->move(public_path("reels"), $reelsFileName);
            $reelsUrl = url("reels", $reelsFileName);
            $newReels->reelsUrl = $reelsUrl;
        } else {return response()->json("Please give video");}
        $newReels->userId = $user->id;

        $newReels->save();

        $userReelsArray = json_decode($user->reels, true);
        array_push($userReelsArray, $newReels->id);
        $user->reels = json_encode($userReelsArray);
        $user->save();

        return response()->json($newReels);
    }

    public function deleteReels(Request $req) {
        $user = User::find($req->query("userid"));
        if(!$user){return response()->json("Please Correct user infos");}
        $reel = Reels::find($req->query("reelsid"));
        if(!$reel){return response()->json("Please Correct reels infos");}

        if ($reel->userId == $user->id) {
            Reels::destroy($reel->id);

            $userReelsArray = json_decode($user->reels, true);
            $index = array_search($req->query("reelsid"), $userReelsArray);
            array_splice($userReelsArray, $index, 1);
            $user->reels = json_encode($userReelsArray);
            $user->save();

            return response()->json(true);
        } 
        
        return response()->json("not Successfuly");
    }

    public function likeReels(Request $req){
        $user = User::find($req->query("userid"));
        if (!$user) {
            return response()->json("Please give Correct user info");
        } 
        $reel = Reels::find($req->query("reelsid"));
        if(!$reel){
            return response()->json("Please give Correct reels info");
        }

        $likeArray = json_decode($reel->like);
        if (in_array($user->id, $likeArray)) {
            $index = array_search($user->id, $likeArray);
            array_splice($likeArray, $index, 1);
        } else {
            array_push($likeArray, $user->id);
        }
        $reel->like = json_encode($likeArray);

        $reel->save();

        return response()->json(["likeCount"=>count($likeArray)]);
    }

    public function addViews(Request $req){
        $reel = Reels::find($req->query("reelsid"));
        if(!$reel){
            return response()->json("Please give correct reel info");
        }

        $reel->views += 1;

        $reel->save();

        return response()->json(["views"=>$reel->views]);
    }
}
