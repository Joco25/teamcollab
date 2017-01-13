<?php

namespace App\Http\Controllers;

use Input;
use Auth;
use App\Topic;
use App\TopicPost;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiTopicPostController extends Controller
{
	public function store()
	{
		$inputs = Input::all();

		$topic = Topic::whereId($inputs['topic_id'])
			->whereTeamId(Auth::user()->team_id)
			->first();

		if (! $topic) abort("Could not find topic.");

		$topic->touch();

		$post = TopicPost::create([
			'user_id' => Auth::user()->id,
			'team_id' => Auth::user()->team_id,
			'body' => $inputs['body'],
			'topic_id' => $topic->id,
			'topic_post_id' => Input::get('topic_post_id', 0)
		]);

		// _::each($s['user_ids'], function($user_id) use ($topic) {
		// 	$topic->add_notifications($user_id);
		// });

		$post->user;
		$post->topic->updatePostCount();
		// $post->topic->send_notifications($post);

		return response()->json([
			'post' => $post
		]);
	}

	public function update($id = 0)
	{
		$post = TopicPost::whereId($id)
			->whereUserId(Auth::user()->id)
			->whereTeamId(Auth::user()->team_id)
			->first();

		if (! $post) abort(422);

		$post->body = Input::get('body');
		$success = $post->save();

		return response()->json([
			'post' => $post,
			'success' => $success
		]);
	}

	public function destroy($id)
	{
		$post = TopicPost::whereId($id)
			->whereUserId(Auth::user()->id)
			->whereTeamId(Auth::user()->team_id)
			->first();

		if (! $post) abort(422);

		$topic = $post->topic;
		$success = $post->delete();
		$topic->updatePostCount();

		return response()->json([
			'success' => $success
		]);
	}

	public function createLike()
	{
		$post = TopicPost::whereId(Input::get('topic_post_id'))
			->whereTeamId(Auth::user()->team_id)
			->first();

		if (! $post) abort(422);

		$success = $post->createLike(Auth::user()->id);
		$post->topic->updateLikeCount();

		return response()->json([
			'success' => (bool) $success
		]);
	}

	public function deleteLike()
	{
		$post = TopicPost::whereId(Input::get('topic_post_id'))
			->whereTeamId(Auth::user()->team_id)
			->first();

		if (! $post) abort(422);

		$success = $post->deleteLike(Auth::user()->id);
		$post->topic->updateLikeCount();

		return response()->json([
			'success' => (bool) $success
		]);
	}

}
