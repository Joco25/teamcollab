<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['user_id', 'team_id', 'name'];

    public function cards()
    {
        return $this->belongsToMany('\App\Card')
            ->whereTeamId(\Auth::user()->team_id);
    }
}
