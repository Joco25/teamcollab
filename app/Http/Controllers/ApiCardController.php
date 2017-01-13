<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Input;
use Auth;
use App\Project;
use App\Card;
use App\Stage;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiCardController extends Controller
{
    public function updateTags()
    {
        $tags = \Input::get('tags');
        $card_id = \Input::get('card_id');

        $card = \App\Card::whereId($card_id)
            ->whereTeamId(\Auth::user()->team_id)
            ->first();

        \DB::table('card_tag')
            ->whereCardId($card_id)
            ->delete();

        foreach ($tags as $tagName)
        {
            if (empty($tagName)) continue;

            $tag = \App\Tag::whereName($tagName)
                ->whereTeamId(\Auth::user()->team_id)
                ->first();

            if (! $tag)
            {
                $tag = \App\Tag::create([
                    'name' => $tagName,
                    'user_id' => \Auth::user()->id,
                    'team_id' => \Auth::user()->team_id
                ]);
            }

            $card->tags()->attach($tag->id);
        }

        $card = \App\Card::with('stage.project', 'users', 'comments.user', 'subtasks', 'tags')
            ->whereId($card_id)
            ->whereTeamId(\Auth::user()->team_id)
            ->first();

        return response()->json([
            'card' => $card
        ]);
    }

    public function updateUsers()
    {
        $userIds = Input::get('user_ids');
        $cardId = Input::get('card_id');

        $card = \App\Card::whereId($cardId)
            ->whereTeamId(\Auth::user()->team_id)
            ->first();

        \DB::table('card_user')
            ->whereCardId($cardId)
            ->delete();

        foreach ($userIds as $userId)
        {
            $card->users()->attach($userId);
        }

        $card = \App\Card::with('stage.project', 'users', 'comments.user', 'subtasks', 'tags')
            ->whereId($cardId)
            ->whereTeamId(\Auth::user()->team_id)
            ->first();

        return response()->json([
            'card' => $card
        ]);
    }

    public function updateStageOrder()
    {
        $cardIds = \Input::get('card_ids');
        $stageId = \Input::get('stage_id');
        foreach ($cardIds as $key => $cardId)
        {
            \App\Card::whereId($cardId)
                ->whereTeamId(\Auth::user()->team_id)
                ->update([
                    'priority' => $key,
                    'stage_id' => $stageId
                ]);
        }

        return response()->json([
            'success' => true
        ]);
    }

    public function updateStage($id)
    {
        $stageId = Input::get('stage_id');
        $cards = Card::whereStageId($stageId)
            ->whereTeamId(Auth::user()->team_id)
            ->orderBy('priority')
            ->get();

        foreach ($cards as $key => $card)
        {
            $card->priority = $key;
            $card->save();
        }

        $success = Card::whereId($id)
            ->whereTeamId(\Auth::user()->team_id)
            ->update([
                'priority' => count($cards),
                'stage_id' => $stageId
            ]);

        $card = \App\Card::whereId($id)
            ->with('stage.project.stages', 'users', 'comments.user', 'subtasks', 'tags', 'attachments')
            ->whereTeamId(\Auth::user()->team_id)
            ->first();

        return response()->json([
            'card' => $card,
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
        //

    }

    public function storeWithoutStage(Request $request)
    {
        $projectId = \Input::get('project_id');
        $cardName = \Input::get('name');

        $project = \App\Project::whereId($projectId)
            ->with('stages')
            ->whereTeamId(\Auth::user()->team_id)
            ->first();

        $success = \App\Card::create([
            'stage_id' => $project->stages[0]->id,
            'name' => $cardName,
            'impact' => 0,
            'user_id' => \Auth::user()->id,
            'team_id' => $project->stages[0]->team_id
        ]);

        $projects = Project::with('stages.cards.subtasks', 'stages.cards.comments', 'stages.cards.tags', 'stages.cards.users', 'stages.cards.attachments', 'stages.cards.stage.project')
            ->whereTeamId(Auth::user()->team_id)
            ->orderBy('priority')
            ->get();

        return response()->json([
            'success' => (bool) $success,
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
        $stageId = \Input::get('stage_id');
        $cardName = \Input::get('name');

        $stage = \App\Stage::whereId($stageId)
            ->whereTeamId(\Auth::user()->team_id)
            ->first();

        $card = \App\Card::create([
            'stage_id' => $stage->id,
            'name' => $cardName,
            'impact' => 0,
            'user_id' => \Auth::user()->id,
            'team_id' => $stage->team_id,
            'priority' => count($stage->cards)
        ]);

        return response()->json([
            'success' => (bool) $card,
            'card' => $card
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
        $card = \App\Card::whereId($id)
            ->with('stage.project.stages', 'users', 'comments.user', 'subtasks', 'tags', 'attachments')
            ->whereTeamId(\Auth::user()->team_id)
            ->first();

        return response()->json([
            'card' => $card
        ]);
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
        $success = \App\Card::whereId($id)
            ->whereTeamId(\Auth::user()->team_id)
            ->update([
                'name' => \Input::get('name'),
                'description' => \Input::get('description'),
                'blocked' => \Input::get('blocked'),
                'impact' => \Input::get('impact')
            ]);

        $card = \App\Card::whereId($id)
            ->with('stage.project', 'users', 'comments.user', 'subtasks', 'tags')
            ->whereTeamId(\Auth::user()->team_id)
            ->first();

        return response()->json([
            'success' => $success,
            'card' => $card
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
        $success = \App\Card::whereId($id)
            ->whereTeamId(\Auth::user()->team_id)
            ->delete();

        return response()->json([
            'success' => $success
        ]);
    }
}
