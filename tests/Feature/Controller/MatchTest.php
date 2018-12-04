<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

/**
 * Test to validate the get maches action.
 */
class MatchTest extends TestCase
{
    use DatabaseTransactions, WithoutMiddleware;

    /**
     * Test the matches action when no exist matches.
     *
     * @test
     */
    public function getAllMatchesEmpty()
    {
        $this->json('GET', '/api/match')
            ->assertSuccessful()
            ->assertExactJson([])
        ;
    }

    /**
     * Test the matches action when exist matches.
     *
     * @test
     */
    public function getAllMatches()
    {
        $result = $this->getMatchesList();

        $this->json('GET', '/api/match')
            ->assertSuccessful()
            ->assertExactJson($result)
        ;
    }

    /**
     * Create a return a list of matches.
     *
     * @return array
     */
    private function getMatchesList()
    {
        $matches = factory(\App\Match::class, 2)->create();
        return [
            [
                'id' => $matches[0]->id,
                'name' => 'Match'.$matches[0]->id,
                'next' => $matches[0]->next,
                'winner' => $matches[0]->winner,
                'combination' => $matches[0]->combination,
                'board' => [0, 0, 0, 0, 0, 0, 0, 0, 0],
            ],
            [
                'id' => $matches[1]->id,
                'name' => 'Match'.$matches[1]->id,
                'next' => $matches[1]->next,
                'winner' => $matches[1]->winner,
                'combination' => $matches[1]->combination,
                'board' => [0, 0, 0, 0, 0, 0, 0, 0, 0],
            ]
        ];
    }
}
