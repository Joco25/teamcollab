<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class TopicPost extends Model
{
    protected $fillable = ['user_id', 'team_id', 'topic_id', 'body', 'topic_post_id'];

	public function topic()
	{
		return $this->belongsTo('\App\Topic');
	}

	public function posts()
	{
		return $this->hasMany('\App\TopicPost');
	}

	public function user()
	{
		return $this->belongsTo('\App\User');
	}

	public function isLiked($user_id)
	{
		$count = TopicPostLike::whereUserId($user_id)
            ->whereTeamId(Auth::user()->team_id)
			->whereTopicPostId($this->id)
			->count();

		return $count > 0;
	}

	public function createLike($user_id)
	{
		return TopicPostLike::create([
			'user_id' => $user_id,
            'team_id' => Auth::user()->team_id,
			'topic_post_id' => $this->id
		]);
	}

	public function deleteLike($user_id)
	{
		return TopicPostLike::whereUserId($user_id)
			->whereTopicPostId($this->id)
            ->whereTeamId(Auth::user()->team_id)
			->delete();
	}
}
