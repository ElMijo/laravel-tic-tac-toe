<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Move extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'move', 'position', 'user_id', 'match_id' ];

    /**
     * Get User model which made the move.
     *
     * @return \App\User
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get Match model where made the move.
     *
     * @return \App\Match
     */
    public function match()
    {
        return $this->belongsTo('App\Match');
    }
}
