<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Input;
use Auth;
use App\Project;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiProjectController extends Controller
{
    public function updateOrder()
    {
        $projectIds = Input::get('project_ids');
        foreach ($projectIds as $key => $projectId)
        {
            Project::whereId($projectId)
                ->whereTeamId(Auth::user()->team_id)
                ->update([
                    'priority' => $key
                ]);
        }

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $projects = Project::with('stages.cards.subtasks', 'stages.cards.comments', 'stages.cards.tags', 'stages.cards.users', 'stages.cards.attachments', 'stages.cards.stage.project')
            ->whereTeamId(Auth::user()->team_id)
            ->orderBy('priority')
            ->get();

        return response()->json([
            'projects' => $projects
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
        $stages = Input::get('stages');

        $priority = is_null(Auth::user()->team) ? 0 : count(Auth::user()->team->projects);
        $project = Project::create([
            'user_id' => Auth::user()->id,
            'team_id' => Auth::user()->team_id,
            'name' => Input::get('name'),
            'priority' => $priority
        ]);

        foreach ($stages as $key => $stage)
        {
            \App\Stage::create([
                'user_id' => Auth::user()->id,
                'team_id' => Auth::user()->team_id,
                'project_id' => $project->id,
                'name' => $stage['name'],
                'priority' => $key
            ]);
        }

        $projects = Project::with('stages.cards.subtasks', 'stages.cards.comments', 'stages.cards.tags', 'stages.cards.users', 'stages.cards.attachments')
            ->whereTeamId(Auth::user()->team_id)
            ->orderBy('priority')
            ->get();

        return response()->json([
            'projects' => $projects
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
    public function update(Request $request, $id)
    {
        //
        $success = Project::whereId($id)
            ->whereTeamId(Auth::user()->team_id)
            ->update([
                'name' => Input::get('name')
            ]);

        $project = Project::with('stages.cards')
            ->whereId($id)
            ->whereTeamId(Auth::user()->team_id)
            ->first();

        return response()->json([
            'project' => $project
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
        $success = Project::whereId($id)
            ->whereTeamId(Auth::user()->team_id)
            ->delete();

        $projects = Project::with('stages.cards')
            ->whereTeamId(Auth::user()->team_id)
            ->get();

        return response()->json([
            'success' => $success,
            'projects' => $projects
        ]);
    }
}
