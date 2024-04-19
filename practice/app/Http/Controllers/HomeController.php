<?php

namespace App\Http\Controllers;

use App\Events\NewNotification;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    function class_basename($class)
    {
        $class = is_object($class) ? get_class($class) : $class;

        return basename(str_replace('\\', '/', $class));
    }

    public function index()
    {

       $user=new User();
//       $user->query()->dd();
       dd(class_basename(new User()));

    }

    public function save_comment(Request $request)
    {
        Comment::create([
            'body'=> $request->post_content,
            'user_id'=>Auth::user()->id,
            'post_id'=>$request->post_id
        ]);

        $data=[
            'comment'=> $request->post_content,
            'user_id'=>Auth::user()->id,
            'post_id'=>$request->post_id
        ];
        event(new NewNotification($data));
        return redirect()->back()->with(['success'=>'post added successfully']);
    }
}
