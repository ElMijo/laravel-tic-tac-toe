<?php

namespace App\Transformer;

use App\Match;

/**
 * Tranformer for the match model.
 */
class MatchTransformer extends \League\Fractal\TransformerAbstract
{
    /**
     * Transform the model data.
     *
     * @param  \App\Match  $match
     *
     * @return array
     */
    public function transform(Match $match)
    {
        return [
            'id' => $match->id,
            'name' => 'Match'.$match->id,
            'next' => intval($match->next),
            'winner' => $match->winner,
            'combination' => $match->combination,
            'board' => $this->makeBoard($match),
        ];
    }

    /**
     * Make a board using match moves.
     *
     * @param  \App\Match  $match
     *
     * @return array
     */
    public function makeBoard(Match $match)
    {
        $sorted = $match->moves()->get()->sortBy('position');
        $keys = $sorted->pluck('position')->all();
        $values = $sorted->pluck('move')->all();
        return array_replace(
            [0, 0, 0, 0, 0, 0, 0, 0, 0],
            array_combine($keys, $values)
        );
    }
}
