<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReelsController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix("/auth")->group(function(){
    Route::post("/register", [AuthController::class, "register"]);
    Route::post("/login", [AuthController::class, "login"]);
    Route::post("/forgotpassword", [AuthController::class, "forgotPassword"]);
    Route::post("/newpassword", [AuthController::class, "newPassword"]);// querystring --> token
    Route::get("/search", function(Request $req){
        $users = User::where('name', 'like', '%'.$req->query("searchtxt").'%')->get();
        return response()->json($users);
    });
    Route::get("/allusers", function(){
        $users = User::all();
        return response()->json($users);
    });
});

Route::post("/addstory", [AuthController::class, "addStories"]);// querystring --> userid
Route::get("/story", [AuthController::class, "getStories"]);// querystring --> userid

Route::prefix("/posts")->group(function(){
    Route::get("/like", [PostController::class, "likePost"]);// querystring --> userid, postid
    Route::get("/unlike", [PostController::class, "unLikePost"]);// querystring --> userid, postid
    Route::get("/views", [PostController::class, "addViews"]);// querystring --> postid
    
    Route::get("/", [PostController::class, "getAllPost"]);
    Route::get("/{id}", [PostController::class, "getPostById"]);
    Route::post("/", [PostController::class, "addNewPost"]);// querystring --> userid
    Route::delete("/{postid}", [PostController::class, "deletePost"]);// querystring --> userid
    Route::post("/{postid}", [PostController::class, "editPost"]);// querystring --> userid
    Route::post("/addcomments", [CommentsController::class, "addCommentPost"]);

});

Route::prefix("/reels")->group(function(){
    Route::get("/like", [ReelsController::class, "likeReels"]);//querystring --> userid, reelsid
    Route::get("/views", [ReelsController::class, "addViews"]);//querystring --> reelsid
    
    Route::get("/", [ReelsController::class, "getReelsByOffset"]);//querystring --> offset
    Route::post("/add", [ReelsController::class, "addReels"]);//querystring --> userid
    Route::delete("/{reelsid}", [ReelsController::class, "deleteReels"]);//querystring --> userid
    Route::post("/addcomments", [CommentsController::class, "addCommentReels"]);

});

Route::get("/getnotifications", [NotificationController::class, "getNotifications"]);

Route::prefix("/chats")->group(function(){
    Route::post("/newchat", [MessageController::class, "newChat"]);
    Route::post("/send", [MessageController::class, "send"]);
    Route::get("/getmessage", [MessageController::class, "getMessages"]);
    Route::get("/getchats", [MessageController::class, "getChats"]);
});
