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
     * Test the response when the match not exists.
     *
     * @test
     */
    public function getNotFoundMatch()
    {
        $this->json('GET', '/api/match/any')
            ->assertNotFound()
            ->assertExactJson([])
        ;
    }

    /**
     * Test the response when the match found.
     *
     * @test
     */
    public function getMatch()
    {
        $match = factory(\App\Match::class)->create();

        $this->json('GET', '/api/match/'.$match->id)
            ->assertSuccessful()
            ->assertExactJson([
                'id' => $match->id,
                'name' => 'Match'.$match->id,
                'next' => $match->next,
                'winner' => $match->winner,
                'combination' => $match->combination,
                'board' => [0, 0, 0, 0, 0, 0, 0, 0, 0],
            ])
        ;
    }

    /**
     * Test the response when create a match.
     *
     * @test
     */
    public function createMatch()
    {
        $this->json('POST', '/api/match')
            ->assertSuccessful()
            ->assertJsonFragment([
                'next' => '0',
                'winner' => '0',
                'combination' => '0',
                'board' => [0, 0, 0, 0, 0, 0, 0, 0, 0],
            ])
        ;
    }

    /**
     * Test the matches action when match not found.
     *
     * @test
     */
    public function deleteMatchNotFound()
    {
        $result = $this->getMatchesList();
        $this->json('DELETE', '/api/match/any')
            ->assertSuccessful()
            ->assertExactJson($result);
        ;
    }

    /**
     * Test the matches action when exist matches.
     *
     * @test
     */
    public function deleteMatch()
    {
        $match = factory(\App\Match::class)->create();
        $result = $this->getMatchesList();
        $this->json('DELETE', '/api/match/'.$match->id)
            ->assertSuccessful()
            ->assertExactJson($result);
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
