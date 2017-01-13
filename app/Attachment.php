<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    //
    protected $fillable = ['user_id', 'team_id', 'card_id', 'filename', 'file_url', 'original_filename', 'file_size'];

    public function card()
    {
        return $this->belongsTo('\App\Card');
    }
}
