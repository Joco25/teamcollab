<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    //
    protected $fillable = ['name', 'user_id', 'project_id', 'priority', 'team_id'];

    public function project() {
        return $this->belongsTo('\App\Project')
            ->orderBy('priority');
    }

    public function cards()
    {
        return $this->hasMany('\App\Card')
            ->orderBy('priority');
    }
}
