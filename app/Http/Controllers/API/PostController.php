<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Http\Controllers\Controller;
use App\Post;
use App\User;
use Carbon\Carbon;
use Session;
use Auth;

class PostController extends Controller
{
    public function index($filter, $user){
        $date = Carbon::now()->subDays(7);
        switch ($filter) {
            case 'new':
                $posts = Post::withCount('likes')->where('created_at', '>', $date)->with('author')->get();
                break;
            case 'all':
                $posts = Post::withCount('likes')->with('author')->get();
                break;
            case 'mine':
                $posts = Post::where('user_id', $user)->withCount('likes')->with('author')->get();
                break;
            default:
                $posts = Post::withCount('likes')->with('author')->get();
        }

        return response()->json(compact('posts', 'user', 'filter'));
    }

    public function getParsedDate(Request $request){
        $date = $request['date'];
        setlocale(LC_TIME, 'Portuguese');
        Carbon::setlocale('br');
        $date = Carbon::parse($date);
        $newDate = $date->diffForHumans(Carbon::now());

        return response()->json(compact('newDate'));
    }

    public function show($id){
        $post = Post::find($id);
        $post->author = $post->author;
        $likes = $post->likes->count();

        return response()->json(compact('post', 'likes'));
    }

    public function create(PostRequest $request){
        try {
            if ($request->file('image')) {
                $file = $request->file('image');
                $input = $request->all();

                $path = $file->store(
                    'post_images/' . $input->id, 'public'
                );

                $extension = $file->getClientOriginalExtension();

                $post = Post::create([
                    'title' => $input->title,
                    'body' => $input->body,
                    'user_id' => Session::get('userId'),
                    'image_url' => $path,
                    'image_extension' => $extension
                ]);

                $success = true;

            }else {
                $input = $request->all();
                $post = Post::create([
                    'title' => $request['title'],
                    'body' => $request['body'],
                    'user_id' => $request['user_id'],
                ]);

                $success = true;
            }

        } catch (Exception $e) {
            $success = false;
            $msg = $e->err;

            return response()->json(compact('msg', 'success'));
        }

        return response()->json(compact('post', 'success'));
    }

    public function getLikes($postId){
        $post = Post::find($postId);
        $likes = $post->likes->count();

        return response()->json(compact('likes'));
    }

    public function likePost($id, $user_id){
        $post = Post::find($id);
        $user = User::find($user_id);

        if ($post->likes()->where('liked_by', $user->id)->count() > 0){
            $post->likes()->detach($user);
        }else{
            $post->likes()->attach($user);
        }

        $likes = $post->likes->count();

        return response()->json(['success' => true, 'likes' => $likes]);
    }

    public function favoritePost($postId){
        $post = Post::find($postId);
        $user = User::find(Session::get('userId'));

        if ($post->favorites()->where('favorited_by', $user->id) > 0) {
            $post->favorites()->detach($user);
        }else{
            $post->favorites()->attach($user);
        }

        return response()->json(['success' => true]);
    }
}
