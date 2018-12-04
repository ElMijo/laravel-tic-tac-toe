<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'next', 'winner', 'combination', 'status' ];

    /**
     * Get a collection of moves.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function moves()
    {
        return $this->hasMany('App\Move');
    }
}
