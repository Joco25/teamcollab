<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiCommentController extends Controller
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
        $comment = \App\Comment::create([
            'user_id' => \Auth::user()->id,
            'team_id' => \Auth::user()->team_id,
            'body' => \Input::get('body'),
            'card_id' => \Input::get('card_id')
        ]);

        $comment->user;

        return response()->json([
            'comment' => $comment
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
        $success = \App\Comment::whereId($id)
            ->whereTeamId($id)
            ->update([
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
        $success = \App\Comment::whereId($id)
            ->whereTeamId(Auth::user()->team_id)
            ->delete();

        return response()->json([
            'success' => $success
        ]);
    }
}
