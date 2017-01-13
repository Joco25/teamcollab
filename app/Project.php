<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //
    protected $fillable = ['name', 'user_id', 'team_id', 'priority'];

    public function stages() {
        return $this->hasMany('\App\Stage')
            ->orderBy('priority');
    }

}
