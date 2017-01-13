<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Hash;
use Auth;
use Input;
use App\Team;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiUserController extends Controller
{
    public function updateMe()
    {
        Auth::user()->name = Input::get('name');
        Auth::user()->email = Input::get('email');
        $success = Auth::user()->save();

        return response()->json([
            'success' => $success
        ]);
    }

    public function updatePassword()
    {
        if (Input::get('password') != Input::get('password_confirm'))
        {
            return response()->json([
                'error' => "Passwords don't match."
            ]);
        }

        Auth::user()->password = Hash::make(Input::get('password'));
        $success = Auth::user()->save();

        return response()->json([
            'success' => $success
        ]);
    }

    public function updateTeam()
    {
        $teamId = Input::get('team_id');
        Auth::user()->team_id = $teamId;
        $success = Auth::user()->save();

        return response()->json([
            'success' => $success
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $team = Team::with('users')
            ->whereId(Auth::user()->team_id)
            ->first();

        return response()->json([
            'users' => $team->users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
