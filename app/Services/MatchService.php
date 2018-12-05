<?php

namespace App\Services;

/**
 * This class manager all process of the match.
 */
class MatchService
{
    public function isUserTurn(\App\Match $match, \App\User $user)
    {
        return $match->next == $this->getUserMove($match, $user);
    }

    public function isPositionAvailable(\App\Match $match, int $position)
    {
        return in_array($position, $this->getPositionsAvailable($match));
    }

    public function isGameOver(\App\Match $match)
    {
        return count($this->getPositionsAvailable($match)) == 0;
    }

    public function getUserMove(\App\Match $match, \App\User $user)
    {
        $moves = $match->moves()->get();
        if (!$move = $moves->where("user_id", "=", $user->id)->pluck('move')->first()) {
            $move = $moves->where("move", "=", 1)->count() > 0 ? 2 : 1;
        }
        return $move;
    }

    protected function getPositionsAvailable(\App\Match $match)
    {
        return array_diff([0,1,2,3,4,5,6,7,8], $match->moves()->get()->pluck('position')->all());
    }

    /**
     * Process board data to determinate if exists a winner.
     */
    public function processWinner(\App\Match &$match)
    {
        $data = $match->board();
        foreach ($this->winningCombination() as $key => $value) {
            list($one, $two, $three) = $value;
            if ($data[$one] && $data[$two] && $data[$three]
                && $data[$one] == $data[$two] && $data[$two] == $data[$three]) {
                $match->next = "0";
                $match->winner = strval($data[$one]);
                $match->combination = strval($key + 1);
                break;
            }
        }
    }

    /**
     * Get list of winning combinations.
     *
     * @var array
     */
    private function winningCombination()
    {
        return [
            [0,1,2],
            [3,4,5],
            [6,7,8],
            [0,3,6],
            [1,4,7],
            [2,5,8],
            [0,4,8],
            [2,4,6],
        ];
    }
}
