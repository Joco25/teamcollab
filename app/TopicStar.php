<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TopicStar extends Model
{
    protected $fillable = ['user_id', 'topic_id', 'team_id'];
}
