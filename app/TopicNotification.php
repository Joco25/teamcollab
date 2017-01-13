<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TopicNotification extends Model
{
    protected $fillable = ['user_id', 'team_id', 'topic_id', 'type'];

	public function user()
	{
		return $this->belongsTo('\App\User');
	}
}
