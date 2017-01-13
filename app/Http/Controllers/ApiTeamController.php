<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Input;
use App\User;
use App\Team;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiTeamController extends Controller
{
    public function deleteUser($id)
    {
        $userId = Input::get('user_id');

        $team = Team::whereId($id)
            ->whereUserId(Auth::user()->id)
            ->first();

        if (! $team) abort(404);

        $success = $team->users()->detach($userId);

        $team = Team::with('users')
            ->whereId($id)
            ->whereUserId(Auth::user()->id)
            ->first();

        return response()->json([
            'success' => (bool) $success,
            'team' => $team
        ]);
    }

    public function createUser()
    {
        $email = Input::get('email');
        $teamId = Input::get('team_id');

        $team = Team::whereId($teamId)
            ->whereUserId(Auth::user()->id)
            ->first();

        $user = User::whereEmail($email)
            ->first();

        if (! $user)
        {
            $user = User::create([
                'email' => $email,
                'team_id' => $team->id
            ]);
        }

        $success = $team->users()->attach($user->id);

        $team = Team::with('users')
            ->whereId($teamId)
            ->whereUserId(Auth::user()->id)
            ->first();

        return response()->json([
            'success' => (bool) $success,
            'team' => $team
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $teams = \Auth::user()->teams;

        return response()->json([
            'teams' => $teams
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $team = \App\Team::create([
            'user_id' => \Auth::user()->id,
            'name' => \Input::get('name')
        ]);

        $team->users()->attach(\Auth::user()->id);
        $teams = \Auth::user()->teams;

        return response()->json([
            'teams' => $teams
        ]);
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
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $name = Input::get('name');

        $teams = Auth::user()->teams->all();
        $hasTeam = array_filter($teams, function($team) use (&$id)
        {
            return $id == $team->id;
        });

        if (is_null($hasTeam))
        {
            abort(403, 'Unauthorized action.');
        }

        $success = Team::whereId($id)
            ->update([
                'name' => $name
            ]);

        return response()->json([
            'success' => $success
        ]);
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
        $success = \App\Team::whereId($id)
            ->whereUserId(\Auth::user()->id)
            ->delete();

        $teams = \Auth::user()->teams;

        return response()->json([
            'success' => $success,
            'teams' => $teams
        ]);
    }
}
