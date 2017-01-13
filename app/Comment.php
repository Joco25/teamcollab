<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $fillable = ['user_id', 'team_id', 'body', 'card_id'];

    public function user()
    {
        return $this->belongsTo('\App\User');
    }
}
