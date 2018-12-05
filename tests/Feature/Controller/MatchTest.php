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
                'next' => intval($match->next),
                'winner' => intval($match->winner),
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
            ->assertJson([
                [
                    'next' => 1,
                    'winner' => 0,
                    'combination' => '0',
                    'board' => [0, 0, 0, 0, 0, 0, 0, 0, 0],
                ]
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
     * Test the matches action when exist matches.
     *
     * @test
     */
    public function deleteMatchWithMoves()
    {
        $user = factory(\App\User::class)->create();
        $match = factory(\App\Match::class)->create(["next" => 2]);

        factory(\App\Move::class)->states('position-1', 'move-1')->create(['match_id' => $match->id, "user_id" => $user->id]);

        $result = $this->getMatchesList();
        $this->json('DELETE', '/api/match/'.$match->id)
            ->assertSuccessful()
            ->assertExactJson($result);
        ;
    }

    /**
     * Test match move action when match not found.
     *
     * @test
     */
    public function moveMatchNotFound()
    {
        $user = factory(\App\User::class)->create();

        $this->actingAs($user)->json('PUT', '/api/match/any', ['position' => 1])
            ->assertNotFound()
            ->assertExactJson(["message" => "The  match [any] not found"]);
        ;
    }

    /**
     * Test match move action when it's not the player's turn.
     *
     * @test
     */
    public function moveIsNotPlayerTurn()
    {
        $user = factory(\App\User::class)->create();
        $match = factory(\App\Match::class)->create(["next" => "2"]);

        $this->actingAs($user)->json('PUT', '/api/match/'.$match->id, ['position' => 1])
            ->assertStatus(400)
            ->assertExactJson(["message" => "it's not your turn"]);
        ;
    }

    /**
     * Test match move action when the position was already played.
     *
     * @test
     */
    public function movePositionAlreadyPlayed()
    {
        $userOne = factory(\App\User::class)->create();
        $userTwo = factory(\App\User::class)->create();
        $match = factory(\App\Match::class)->create(["next" => 2]);

        factory(\App\Move::class)->states('position-1', 'move-1')->create(['match_id' => $match->id, "user_id" => $userOne->id]);

        $this->actingAs($userTwo)->json('PUT', '/api/match/'.$match->id, ['position' => 1])
            ->assertStatus(400)
            ->assertExactJson(["message" => "The position was already played"]);
        ;
    }

    /**
     * Test match move action when the match was over without winner.
     *
     * @test
     */
    public function moveMatchOverWithoutWinner()
    {
        $userOne = factory(\App\User::class)->create();
        $userTwo = factory(\App\User::class)->create();
        $match = factory(\App\Match::class)->create();
        factory(\App\Move::class)->states('position-0', 'move-1')->create(['match_id' => $match->id, "user_id" => $userOne->id]);
        factory(\App\Move::class)->states('position-1', 'move-2')->create(['match_id' => $match->id, "user_id" => $userTwo->id]);
        factory(\App\Move::class)->states('position-2', 'move-1')->create(['match_id' => $match->id, "user_id" => $userOne->id]);
        factory(\App\Move::class)->states('position-3', 'move-1')->create(['match_id' => $match->id, "user_id" => $userOne->id]);
        factory(\App\Move::class)->states('position-4', 'move-2')->create(['match_id' => $match->id, "user_id" => $userTwo->id]);
        factory(\App\Move::class)->states('position-5', 'move-2')->create(['match_id' => $match->id, "user_id" => $userTwo->id]);
        factory(\App\Move::class)->states('position-6', 'move-2')->create(['match_id' => $match->id, "user_id" => $userTwo->id]);
        factory(\App\Move::class)->states('position-7', 'move-1')->create(['match_id' => $match->id, "user_id" => $userOne->id]);
        factory(\App\Move::class)->states('position-8', 'move-1')->create(['match_id' => $match->id, "user_id" => $userOne->id]);

        $this->actingAs($userOne)->json('PUT', '/api/match/'.$match->id, ['position' => 1])
            ->assertStatus(400)
            ->assertExactJson(["message" => "The game is over"]);
        ;
    }


    /**
     * Test the matches action when exist matches.
     *
     * @test
     */
    public function moveSuccesfully()
    {
        $userOne = factory(\App\User::class)->create();
        $userTwo = factory(\App\User::class)->create();
        $match = factory(\App\Match::class)->create(["next" => "1", "combination" => "0", "winner" => "0"]);

        factory(\App\Move::class)->states('position-0', 'move-1')->create(['match_id' => $match->id, "user_id" => $userOne->id]);
        factory(\App\Move::class)->states('position-3', 'move-2')->create(['match_id' => $match->id, "user_id" => $userTwo->id]);
        factory(\App\Move::class)->states('position-4', 'move-1')->create(['match_id' => $match->id, "user_id" => $userOne->id]);
        factory(\App\Move::class)->states('position-8', 'move-2')->create(['match_id' => $match->id, "user_id" => $userTwo->id]);
        factory(\App\Move::class)->states('position-7', 'move-2')->create(['match_id' => $match->id, "user_id" => $userTwo->id]);

        $this->actingAs($userOne)->json('PUT', '/api/match/'.$match->id, ['position' => 1])
            ->assertSuccessful()
            ->assertExactJson([
                'id' => $match->id,
                'name' => 'Match'.$match->id,
                'next' => 2,
                'winner' => 0,
                'combination' => "0",
                'board' => [1, 1, 0, 2, 1, 0, 0, 2, 2],
            ]);
        ;
    }

    /**
     * Test the matches action when exist matches.
     *
     * @test
     */
    public function moveSuccesfullyAndWin()
    {
        $userOne = factory(\App\User::class)->create();
        $userTwo = factory(\App\User::class)->create();
        $match = factory(\App\Match::class)->create(["next" => "1", "combination" => "0", "winner" => "0"]);

        factory(\App\Move::class)->states('position-0', 'move-1')->create(['match_id' => $match->id, "user_id" => $userOne->id]);
        factory(\App\Move::class)->states('position-3', 'move-2')->create(['match_id' => $match->id, "user_id" => $userTwo->id]);
        factory(\App\Move::class)->states('position-4', 'move-1')->create(['match_id' => $match->id, "user_id" => $userOne->id]);
        factory(\App\Move::class)->states('position-8', 'move-2')->create(['match_id' => $match->id, "user_id" => $userTwo->id]);
        factory(\App\Move::class)->states('position-1', 'move-1')->create(['match_id' => $match->id, "user_id" => $userOne->id]);
        factory(\App\Move::class)->states('position-7', 'move-2')->create(['match_id' => $match->id, "user_id" => $userTwo->id]);


        $this->actingAs($userOne)->json('PUT', '/api/match/'.$match->id, ['position' => 2])
            ->assertSuccessful()
            ->assertExactJson([
                'id' => $match->id,
                'name' => 'Match'.$match->id,
                'next' => 0,
                'winner' => 1,
                'combination' => "1",
                'board' => [1, 1, 1, 2, 1, 0, 0, 2, 2],
            ]);
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
                'next' => intval($matches[0]->next),
                'winner' => intval($matches[0]->winner),
                'combination' => $matches[0]->combination,
                'board' => [0, 0, 0, 0, 0, 0, 0, 0, 0],
            ],
            [
                'id' => $matches[1]->id,
                'name' => 'Match'.$matches[1]->id,
                'next' => intval($matches[1]->next),
                'winner' => intval($matches[1]->winner),
                'combination' => $matches[1]->combination,
                'board' => [0, 0, 0, 0, 0, 0, 0, 0, 0],
            ]
        ];
    }
}
