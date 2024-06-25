<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use App\Models\Reels;
use App\Models\User;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function addCommentPost(Request $req){
        try {
            $posts = Posts::find($req->query("postid"));
            $user = User::find($req->query("userid"));
            if ($posts) {
                return response()->json("Post not found");
            }

            $commentArray = json_decode($posts->comments);
            array_push($commentArray, ["_id"=>$req->query("userid"), "name"=>$user->name, 
                "profilePhoto"=>$user->profilePhoto, "comment"=>$req->input("comment")
            ]);
            $posts->comments = json_encode($commentArray);
            $posts->save();

            return response()->json($posts);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addCommentReels(Request $req){
        try {
            $reels = Reels::find($req->query("reelsid"));
            $user = User::find($req->query("userid"));
            if (!$reels) {
                return response()->json("Reels not found");
            }

            $commentArray = json_decode($reels->comments);
            array_push($commentArray, ["_id"=>$req->query("userid"), "name"=>$user->name, 
                "profilePhoto"=>$user->profilePhoto, "comment"=>$req->input("comment")
            ]);
            $reels->comments = json_encode($commentArray);
            $reels->save();

            return response()->json($reels);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
