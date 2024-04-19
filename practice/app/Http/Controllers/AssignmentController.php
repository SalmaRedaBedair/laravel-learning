<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
//        $user=User::factory()
//            ->has(
//                Assignment::factory()
//                    ->count(3)
//                    ->state(function (array $attributes, User $user) {
//                        return ['title' => $user->name . ' title'];
//                    })
//            )
//            ->create();
//        dd($user->Assignments);

        $userId=User::latest()->first()->id;
        $print=DB::table('users')
            ->where('id', $userId)
            ->update(['name' => 'sally', 'email' => 'mhh@gmail.com', 'password' => 'hashed_password', 'plan_rate_limit' => 2]);

        dd($print);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Assignment $assignment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assignment $assignment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Assignment $assignment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assignment $assignment)
    {
        //
    }
}
