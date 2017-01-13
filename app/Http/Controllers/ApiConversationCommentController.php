<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ConversationComment;
use App\Conversation;
use Input;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiConversationCommentController extends Controller
{
	public function store()
	{
		$inputs = Input::all();

        $conversation = Conversation::whereId($inputs['conversation_id'])
			->whereTeamId(Auth::user()->team_id);

        $comment = Comment::create([
            'user_id' => Auth::user()->id,
            'team_id' => Auth::user()->team_id,
            'body' => $inputs['body'],
            'conversation_id' => $inputs['conversation_id']
        ]);

        foreach ($inputs['user_ids'] as $userId)
		{
            $comment->users()->attach($userId);
        }

        $conversation->touch();

        return response()->json([
			'success' => true
		]);
	}

	public function destroy($id)
	{
        $comment = ConversationComment::whereId($id)
			->whereTeamId(Auth::user()->team_id)
			->whereUserId(Auth::user()->id)
				->first();

        if (! $comment)
		{
            abort(401, "Not authorized.");
        }

		$success = $comment->delete();

        return response()->json(compact($success));
	}

	public function createLike($id)
	{
        $comment = ConversationComment::find($id);

        if (! $comment->isLiked(Auth::user()->id))
		{
            $comment->like(Auth::user()->id);
        }

        $comment->likes;

        return response()->json(compact('comment'));
	}

	public function destroyLike($id)
	{
        $comment = ConversationComment::find($id);
        $comment->unlike(Auth::user()->id);
        $comment->likes = $comment->likes;

        return response()->json(compact('comment'));
	}
}
