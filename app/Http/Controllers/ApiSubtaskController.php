<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiSubtaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
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
        $cardId = \Input::get('card_id');

        $card = \App\Card::with('subtasks')
            ->whereTeamId(\Auth::user()->team_id)
            ->whereId($cardId)
            ->first();

        $subtask = \App\Subtask::create([
            'team_id' => \Auth::user()->team_id,
            'user_id' => \Auth::user()->id,
            'body' => \Input::get('body'),
            'checked' => false,
            'card_id' => $card->id,
            'priority' => count($card->subtasks)
        ]);

        return response()->json([
            'subtask' => $subtask
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
        $success = \App\Subtask::whereId($id)
            ->whereTeamId(\Auth::user()->team_id)
            ->update([
                'checked' => \Input::get('checked'),
                'body' => \Input::get('body')
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
        $success = \App\Subtask::whereId($id)
            ->whereTeamId(\Auth::user()->team_id)
            ->delete();

        return response()->json([
            'success' => $success
        ]);
    }
}
