<?php

namespace App;

use DB;
use Auth;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
	protected $fillable = ['user_id', 'team_id', 'name', 'post_count', 'like_count', 'view_count'];

	public function posts()
	{
		return $this->hasMany('\App\TopicPost');
	}

	public function postCount()
	{
		return $this->hasMany('\App\TopicPost')
			->count();
	}

	public function user()
	{
		return $this->belongsTo('\App\User');
	}

	public function isStarred($user_id)
	{
		$count = TopicStar::whereUserId($user_id)
			->whereTopicId($this->id)
			->whereTeamId(Auth::user()->team_id)
			->count();

		return $count > 0;
	}

	public function starCount()
	{
		return TopicStar::whereTopicId($this->id)
			->count();
	}

	public function createStar($user_id)
	{
		return TopicStar::create([
			'user_id' => $user_id,
			'topic_id' => $this->id,
			'team_id' => Auth::user()->team_id
		]);
	}

	public function deleteStar($user_id)
	{
		return TopicStar::whereUserId($user_id)
			->whereTopicId($this->id)
			->whereTeamId(Auth::user()->team_id)
			->delete();
	}

	public function likeCount()
	{
		return Topic::where('topics.id', '=', $this->id)
			->join('topic_posts', 'topic_posts.topic_id', '=', 'topics.id')
			->join('topic_post_likes', 'topic_post_likes.topic_post_id', '=', 'topic_posts.id')
			->count();
	}

	public function users()
	{
		return Topic::where('topics.id', '=', $this->id)
			->join('topic_posts', 'topic_posts.topic_id', '=', 'topics.id')
			->join('users', 'users.id', '=', 'topic_posts.user_id')
			->distinct()
			->get(['users.name', 'users.email']);
	}

	public function updatePostCount()
	{
		Topic::whereId($this->id)
			->update([
				'post_count' => $this->postCount()
			]);
	}

	public function updateLikeCount()
	{
		DB::table('topics')
			->whereId($this->id)
			->update([
				'like_count' => $this->likeCount()
			]);
	}

	public function viewCount()
	{
		return TopicView::whereTeamId(Auth::user()->team_id)
			->whereTopicId($this->id)
			->count();
	}

	public function updateViewCount()
	{
		$this->view_count = $this->viewCount();
		return $this->save();
	}

	public function isUnread()
	{
		// $query = DB::query("
		// 	SELECT COUNT(*) as topic_count
		// 	FROM topics t
		// 	WHERE NOT exists(
		// 		SELECT tuv.topic_id, max(tuv.created_at) as last_view, max(tp.created_at) as last_post
		// 		from topic_user_views tuv
		// 		inner join topic_posts as tp
		// 		on tuv.topic_id=tp.topic_id and tuv.created_at > tp.created_at
		// 		group by topic_id
		// 		having t.id=topic_id)
		// 		AND t.id = {$this->id}
		// ");
        //
		// $topicCounts = DB::table('topics')
		// 	->whereNotExists(function($query) {
		// 		$query->select(DB::raw('topic_views.topic_id, max(topic_views.created_at) as last_view, max(topic_posts.created_at) as last_post'))
		// 			->from('topic_views')
		// 			->join('topic_posts', function($join) {
		// 				$join->on('topic_views.topic_id', '=', 'topic_posts.topic_id')
		// 					->on('topic_views.created_at', '>', 'topic_posts.created_at');
		// 			})
		// 			->groupBy('topic_id')
		// 			->having('topics.id', '=', 'topic_id')
		// 			->having('topics.id', '=', $this->id);
		// 	})
		// 	->count();
		$count = TopicView::whereUserId(Auth::user()->id)
            ->whereTopicId($this->id)
            ->whereTeamId(Auth::user()->team_id)
            ->count();

		return $count == 0;
	}

	public function notifications()
	{
		return $this->hasMany('TopicNotification');
	}

	public function sendNotifications($post)
	{
		$body = "
		<table>
			<tr>
				<td>
					<img src='" . url('/image?image=' . $post->user->image . '&size=50') . "'>
				</td>
				<td>
					<strong>{$post->user->name}</strong>
					{$post->body}
					<p>
						<a href='" . url('profile#/profile/social/topics/' . $this->id) . "'>
							Continue Reading
						</a>
					</p>
				</td>
			</tr>
		</table>
		";

		foreach ($this->notifications as $notification) {
			if ($notification->user_id == Auth::user()->id || !$notification->user) {
				continue;
			}

			$email = Email::create([
				'to' => $notification->user->email,
				'subject' => "New Post In {$this->name}",
				'body' => render("templates.email", [
					'header' => "New Post In {$this->name}",
					'content' => $body
				])
			]);

			Queue::create([
				'email_id' => $email->id,
				'type' => 'send_email',
			]);
		}
	}

	public function removeNotifications($user_id)
	{
		return TopicUserNotification::whereUserId($user_id)
			->whereTopicId($this->id)
			->delete();
	}

	public function addNotifications($user_id)
	{
		$this->removeNotifications($user_id);

		return DB::table('topic_user_notifications')
			->insert([
				'topic_id' => $this->id,
				'user_id' => $user_id,
				'type' => 'watching'
			]);
	}
}
