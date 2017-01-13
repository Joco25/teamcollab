<?php

namespace App\Http\Controllers;

use Auth;
use Input;
use DB;
use App\SimpleTeam\_;
use App\Topic;
use App\TopicPost;
use App\TopicNotification;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiTopicController extends Controller
{
	public function latest()
	{
    	$page = Input::get('page', 0);
        $take = Input::get('take', 50);

		$topics = Topic::forPage($page, $take)
			->whereTeamId(Auth::user()->team_id)
			->orderBy('updated_at', 'desc')
			->get();

		_::each($topics, function($topic) {
            $topic->is_starred = $topic->isStarred(Auth::user()->id);
			$topic->users = $topic->users();
			$topic->is_unread = $topic->isUnread();
			// $topic->created_at = from_utc($topic->created_at, $account->timezone);
			// $topic->updated_at = from_utc($topic->updated_at, $account->timezone);
		});

		return response()->json([
			'topics' => $topics
		]);
	}

	public function starred()
	{
		$inputs = Input::all([
			'page' => 1,
			'take' => 50
		]);

		$topics = Topic::join('topic_stars', 'topic_stars.topic_id', '=', 'topics.id')
			->where('topics.team_id', '=', Auth::user()->team_id)
			->where('topic_stars.user_id', '=', Auth::user()->id)
			->orderBy('topic_stars.id', 'desc')
			->forPage($inputs['page'], $inputs['take'])
			->get(['topics.id', 'topics.name', 'topics.updated_at', 'topics.created_at']);

		_::each($topics, function($topic) {
			$topic->is_starred = $topic->isStarred(Auth::user()->id);
			$topic->users = $topic->users();
			$topic->is_unread = $topic->isUnread();
			// $topic->created_at = from_utc($topic->created_at, $account->timezone);
			// $topic->updated_at = from_utc($topic->updated_at, $account->timezone);
		});

		return response()->json([
			'topics' => $topics
		]);
	}

	public function unread()
	{
		$inputs = Input::all([
			'page' => 1,
			'take' => 50
		]);

		$offset = ($inputs['page'] - 1) * $inputs['take'];

		// http://stackoverflow.com/questions/23461042/selecting-the-last-record-and-comparing-a-datetime?noredirect=1#comment35967018_23461042
		// $topics = DB::query("
		// 	SELECT t.id as id
		// 	FROM `topics` t
		// 	WHERE not exists(
		// 		SELECT tuv.topic_id, max(tuv.created_at) as last_view, max(tp.created_at) as last_post
		// 		from `topic_views` tuv
		// 		inner join `topic_posts` as tp
		// 		on tuv.topic_id=tp.topic_id and tuv.created_at > tp.created_at
		// 		group by `topic_id`
		// 		having t.id=topic_id)
		// 	ORDER BY `id` DESC
		// 	LIMIT {$offset}, " . (int) $inputs['take']
		// );

		$topics = DB::table('topics')
			->where('topics.team_id', '=', Auth::user()->team_id)
			->whereNotExists(function($query) {
				$query->select(DB::raw('topic_views.topic_id, max(topic_views.created_at) as last_view, max(topic_posts.created_at) as last_post'))
					->from('topic_views')
					->join('topic_posts', function($join) {
						$join->on('topic_views.topic_id', '=', 'topic_posts.topic_id')
							->on('topic_views.created_at', '>', 'topic_posts.created_at');
					})
					->groupBy('topic_id')
					->having('topics.id', '=', 'topic_id');
			})
			->orderBy('id')
			->skip($offset)
			->take($inputs['take'])
			->get();

		$topic_ids = _::pluck($topics, 'id');
		$topic_ids = count($topic_ids) > 0 ? $topic_ids : [0];

		$d['topics'] = Topic::whereIn('id', $topic_ids)
			->get();

		_::each($d['topics'], function($topic) {
			$topic->is_starred = $topic->isStarred(Auth::user()->id);
			$topic->users = $topic->users();
			$topic->is_unread = $topic->isUnread();
			// $topic->created_at = from_utc($topic->created_at, $account->timezone);
			// $topic->updated_at = from_utc($topic->updated_at, $account->timezone);
		});

		return response()->json([
			'topics' => $topics
		]);
	}

	public function top()
	{
		$inputs = Input::all([
			'page' => 1,
			'take' => 50
		]);

		$offset = ($inputs['page'] - 1) * $inputs['take'];

		$post_weight = 300;
		$like_weight = 1000000;
		$view_weight = 5;
		$created_at_weight = .4;
		$updated_at_weight = 3;

		$topics = Topic::select([
				'id',
				'name',
				'like_count',
				'post_count',
				'view_count',
				'created_at',
				'updated_at',
				DB::raw("((post_count * {$post_weight}) + (like_count * {$like_weight}) + (view_count * {$view_weight}) + (UNIX_TIMESTAMP(created_at) * {$created_at_weight}) + (UNIX_TIMESTAMP(updated_at) * {$updated_at_weight})) as popularity_score")
			])
			->whereTeamId(Auth::user()->team_id)
			->forPage($inputs['page'], $inputs['take'])
			->orderBy('popularity_score', 'desc')
			->get(['name']);

		_::each($topics, function($topic) {
			$topic->is_starred = $topic->isStarred(Auth::user()->id);
			$topic->users = $topic->users();
			$topic->is_unread = $topic->isUnread();
			// $topic->created_at = from_utc($topic->created_at, $account->timezone);
			// $topic->updated_at = from_utc($topic->updated_at, $account->timezone);
		});

		return response()->json([
			'topics' => $topics
		]);
	}

	public function store()
	{
		$inputs = Input::all([
			'name' => '',
			'body' => '',
			'user_ids' => []
		]);

		$topic = Topic::create([
			'user_id' => Auth::user()->id,
			'team_id' => Auth::user()->team_id,
			'name' => $inputs['name'],
			'post_count' => 1
		]);

		$post = TopicPost::create([
			'user_id' => Auth::user()->id,
			'team_id' => Auth::user()->team_id,
			'body' => $inputs['body'],
			'topic_id' => $topic->id
		]);

		TopicNotification::create([
			'user_id' => Auth::user()->id,
			'topic_id' => $topic->id,
			'type' => 'watching'
		]);

		// _::each($inputs['user_ids'], function($user_id) use ($topic) {
		// 	$topic->add_notifications($user_id);
		// });

		// $topic->send_notifications($post);

		return response()->json([
			'topic' => $topic
		]);
	}

	public function destroy($id)
	{
		$topic = Topic::whereId($id)
			->whereUserId(Auth::user()->id)
			->whereTeamId(Auth::user()->team_id)
			->first();

		if (! $topic) abort(422);

		TopicPost::whereTopicId($topic->id)
			->delete();

		$success = $topic->delete();

		return response()->json([
			'success' => $success
		]);
	}

	public function show($id)
	{
		$topic = Topic::with(['posts', 'posts.user', 'posts.posts', 'posts.posts.user'])
			->find($id);

		if (! $topic)
		{
			abort("Could not find topic.");
		}

		$topic->is_starred = $topic->isStarred(Auth::user()->id);
		// $topic->created_at = from_utc($topic->created_at, $account->timezone);
		// $topic->updated_at = from_utc($topic->updated_at, $account->timezone);

		_::each($topic->posts, function($post) {
			$post->is_liked = $post->isLiked(Auth::user()->id);
		});

		return response()->json([
			'topic' => $topic
		]);
	}

	public function createStar()
	{
		$topic = Topic::whereId(Input::get('topic_id'))
			->whereTeamId(Auth::user()->team_id)
			->first();

		if (! $topic) abort(422);

		$success = $topic->createStar(Auth::user()->id);

		return response()->json([
			'success' => (bool) $success
		]);
	}

	public function deleteStar()
	{
		$topic = Topic::whereId(Input::get('topic_id'))
			->whereTeamId(Auth::user()->team_id)
			->first();

		if (! $topic) abort(422);

		$success = $topic->deleteStar(Auth::user()->id);

		return response()->json([
			'success' => (bool) $success
		]);
	}

	public function post_view($id = 0)
	{
		$topic = Topic::find($id);
		if (! $topic) {
			return Api::error("Can't find topic.");
		}

		$cookie_name = 'topic_user_view_' . $id . '_' . Auth::user()->id;

		TopicUserView::create([
			'user_id' => Auth::user()->id,
			'account_user_id' => Auth::user()->team_id,
			'topic_id' => $id
		]);

		$topic->update_view_count();

		return Api::success();
	}

	public function get_user_notification($topic_id, $user_id)
	{
		$d['notification'] = TopicUserNotification::where_user_id(Auth::user()->id)
			->where_topic_id($topic_id)
			->first();

		return Api::json($d);
	}

	public function delete_user_notification($topic_id, $user_id)
	{
		$topic = Topic::find($topic_id);
		$success = $topic->remove_notifications(Auth::user()->id);

		return Api::success($success);
	}

	public function post_user_notification($topic_id, $user_id)
	{
		$topic = Topic::find($topic_id);
		$d['notification'] = $topic->add_notifications(Auth::user()->id);

		return Api::json($d);
	}
}
