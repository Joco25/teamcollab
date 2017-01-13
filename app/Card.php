<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    //
    protected $fillable = ['name', 'user_id', 'stage_id', 'priority', 'team_id', 'impact'];

    public function attachments()
    {
        return $this->hasMany('\App\Attachment');
    }

    public function stage()
    {
        return $this->belongsTo('\App\Stage');
    }

    public function comments()
    {
        return $this->hasMany('\App\Comment');
    }

    public function subtasks()
    {
        return $this->hasMany('\App\Subtask');
    }

    public function tags()
    {
        return $this->belongsToMany('\App\Tag')
            ->whereTeamId(\Auth::user()->team_id);
    }

    public function users()
    {
        return $this->belongsToMany('\App\User');
    }
}
