<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Project;
use App\Card;
use App\Stage;
use App\Team;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $team = Team::create([
            'user_id' => $user->id,
            'name' => 'My First Team'
        ]);
        $team->users()->attach($user->id);
        $user->team_id = $team->id;
        $user->save();

        $project = Project::create([
            'name' => 'My First Project',
            'team_id' => $team->id,
            'user_id' => $user->id,
            'priority' => 0
        ]);

        $inProgressStage = Stage::create([
            'name' => 'In Progress',
            'team_id' => $team->id,
            'user_id' => $user->id,
            'priority' => 1,
            'project_id' => $project->id
        ]);

        $stage = Stage::create([
            'name' => 'Done',
            'team_id' => $team->id,
            'user_id' => $user->id,
            'priority' => 2,
            'project_id' => $project->id
        ]);

        $newStage = Stage::create([
            'name' => 'New',
            'team_id' => $team->id,
            'user_id' => $user->id,
            'priority' => 0,
            'project_id' => $project->id
        ]);

        $card = Card::create([
            'name' => 'This card was just created and is waiting to get started.',
            'team_id' => $team->id,
            'user_id' => $user->id,
            'stage_id' => $newStage->id,
            'impact' => 45,
            'priority' => 0
        ]);

        $card = Card::create([
            'name' => 'Another task in this project with a lower impact on the project.',
            'team_id' => $team->id,
            'user_id' => $user->id,
            'stage_id' => $newStage->id,
            'impact' => 22,
            'priority' => 1
        ]);

        $card = Card::create([
            'name' => 'This card is in progress and being worked on.  When completed drag to the right to show your team you\'re done.',
            'team_id' => $team->id,
            'user_id' => $user->id,
            'stage_id' => $inProgressStage->id,
            'impact' => 78
        ]);

        return $user;
    }
}
