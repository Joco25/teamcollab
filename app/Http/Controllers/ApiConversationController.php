<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Input;
use App\Conversation;
use App\ConversationComment;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiConversationController extends Controller
{
	public function show()
	{
		
	}

	public function store()
	{
		$inputs = Input::all();

		$conversation = Conversation::create([
			'user_id' => Auth::user()->id,
			'team_id' => Auth::user()->team_id,
			'body' => Input::get('body')
		]);

		$comment = ConversationComment::create([
			'user_id' => Auth::user()->id,
			'team_id' => Auth::user()->team_id,
			'conversation_id' => $conversation->id,
			'body' => Input::get('body')
		]);

		foreach ($inputs['user_ids'] as $userId)
		{
			$comment->users()->attach($user_id);
		}

		return response()->json([
			$success => 1
		]);
	}

	public function destroy($id)
	{
		$conversation = Conversation::find($id);

		if ($conversation->user_id != Auth::user()->id) {
			abort(401, "Not authorized.");
		}

		$success = $conversation->delete();

		return response()->json([
			'success' => $success
		]);
	}
}
