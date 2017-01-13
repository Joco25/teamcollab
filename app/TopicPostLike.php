<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TopicPostLike extends Model
{
    protected $fillable = ['user_id', 'topic_post_id', 'team_id'];
}
