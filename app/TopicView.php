<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TopicView extends Model
{
    protected $fillable = ['user_id', 'team_id', 'topic_id'];
}
