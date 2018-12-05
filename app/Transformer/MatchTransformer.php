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
            'winner' => intval($match->winner),
            'combination' => $match->combination,
            'board' => $match->board(),
        ];
    }
}
