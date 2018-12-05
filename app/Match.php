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

    public function switchNext()
    {
        if ($this->next != 0) {
            $this->next = strval($this->next == 1 ? 2 : 1);
        }
        return $this;
    }

    public function board()
    {
        $sorted = $this->moves()->get()->sortBy('position');
        $keys = $sorted->pluck('position')->all();
        $values = $sorted->pluck('move')->all();
        return array_map('intval', array_replace(
            [0, 0, 0, 0, 0, 0, 0, 0, 0],
            array_combine($keys, $values)
        ));
    }
}
