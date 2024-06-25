<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function getAllPost() {
        $posts = Posts::all();

        return response()->json($posts);
    }

    public function getPostById($id){
        $post = Posts::find($id);

        return response()->json($post);
    }

    public function addNewPost(Request $req){
        $newPost = new Posts();
        $user = User::find($req->query("userid"));
        if ($req->hasFile("postUrl") && $user) {
            $postFile = $req->file("postUrl");
            $postFileName = time()."_".$req->query("userid")."-post-".$postFile->getClientOriginalName();
            $postFile->move(public_path("posts"), $postFileName);
            $postUrl = url("posts", $postFileName);

            $userPostArray = json_decode($user->posts, true);

            $newPost->title = $req->input("title");
            $newPost->description = $req->input("description");
            $newPost->postUrl = $postUrl;
            $newPost->userId = $req->query("userid");
            
            $newPost->save();

            array_push($userPostArray, $newPost->id);
            $user->posts = json_encode($userPostArray);
            
            $user->save();
        } else {
            return response()->json("Please Enter correct infos");
        }

        return response()->json($newPost);
    }

    public function deletePost(Request $req, $postid) {
        $user = User::find($req->query("userid"));
        if(!$user){return response()->json("Please enter userid to query string");}
        $post = Posts::find($postid);
        if(!$post){return response()->json("Please enter correct postid");}

        if ($user->id == $post->userId) {
            Posts::destroy($post->id);
            $userPostArray = json_decode($user->posts, true);
            $index = array_search($postid, $userPostArray);
            array_splice($userPostArray, $index, 1);
            $user->posts = json_encode($userPostArray);

            $user->save();

            return response()->json(true);
        } 

        return response()->json(false);
    }

    public function editPost(Request $req, $postid) {
        $user = User::find($req->query("userid"));
        if(!$user){return response()->json("Please enter userid to query string");}
        $post = Posts::find($postid);
        if(!$post){return response()->json("Please enter correct postid");}

        if ($user->id == $post->userId) {
            $post->title = $req->input("title");
            $post->description = $req->input("description");
            if ($req->hasFile("postUrl")) {
                $postFile = $req->file("postUrl");
                $postFileName = time()."_".$req->query("userid")."-post-".$postFile->getClientOriginalName();
                $postFile->move(public_path("posts"), $postFileName);
                $newPostUrl = url("posts", $postFileName);
                $post->postUrl = $newPostUrl;
            }
            $post->save();
        } 

        return response()->json("This video not deleted, because its not your post");
    }

    public function likePost(Request $req){
        $user = User::find($req->query("userid"));
        if(!$user){return response()->json("Please enter userid to query string");}
        $post = Posts::find($req->query("postid"));
        if(!$post){return response()->json("Please enter correct postid");}

        $unLikeArray = json_decode($post->unLike, true);
        if(in_array($user->id, $unLikeArray)){
            $index = array_search($user->id, $unLikeArray);
            array_splice($unLikeArray, $index, 1);
            $post->unLike = json_encode($unLikeArray);
        }

        $likeArray = json_decode($post->like, true);
        if (in_array($user->id, $likeArray)) {
            $index = array_search($user->id, $likeArray); 
            array_splice($likeArray, $index, 1);
        } else {
            array_push($likeArray, $user->id);
        }
        $post->like = json_encode($likeArray);
        $post->save();

        return response()->json([
            "post"=>$post,
            "likeCount"=>count($likeArray),
            "unLikeCount"=>count($unLikeArray)
        ]);

    }

    public function unLikePost(Request $req) {
        $user = User::find($req->query("userid"));
        if(!$user){return response()->json("Please enter userid to query string");}
        $post = Posts::find($req->query("postid"));
        if(!$post){return response()->json("Please enter correct postid");}

        $likeArray = json_decode($post->like, true);
        if (in_array($user->id, $likeArray)) {
            $index = array_search($user->id, $likeArray);
            array_splice($likeArray, $index, 1);
            $post->like = json_encode($likeArray);
        }

        $unLikeArray = json_decode($post->unLike, true);
        if (in_array($user->id, $unLikeArray)) {
            $index = array_search($user->id, $unLikeArray);
            array_splice($unLikeArray, $index, 1);
            $post->unLike = json_encode($unLikeArray);
        } else {
            array_push($unLikeArray, $user->id);
        }
        $post->unLike = json_encode($unLikeArray);
        $post->save();

        return response()->json([
            "post"=>$post,
            "likeCount"=>count($likeArray),
            "unLikeCount"=>count($unLikeArray)
        ]);
    }

    public function addViews(Request $req) {
        $post = Posts::find($req->query("postid"));
        if(!$post){return response()->json("Please enter correct postid");}

        $post->views = $post->views + 1; 
        $post->save();
        
        return response()->json(["views"=>$post->views]);
    }
}
