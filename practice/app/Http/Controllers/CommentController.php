<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        return response()->json(['message' => 'Hello World!']);
    }
    public function save_comment(ProfileUpdateRequest $request)
    {
        dd($request->all());
    }
}
