<?php

namespace Tests\Unit\Transformer;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Transformer\MatchTransformer;

class MatchTransformerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var \App\Transformer\MatchTransformer
     */
    private $transformer;

    public function setUp()
    {
        parent::setUp();
        $this->transformer = $this->app->make('app.transformer.match');
        $this->assertInstanceOf(MatchTransformer::class, $this->transformer);
    }

    /**
     * Test the transformation when the match do not have moves.
     *
     * @test
     */
    public function transformMatchWithEmptyMoves()
    {
        $match = factory(\App\Match::class)->make();

        $this->assertEquals(
            [
                'id' => $match->id,
                'name' => 'Match'.$match->id,
                'next' => $match->next,
                'winner' => $match->winner,
                'combination' => $match->combination,
                'board' => [0, 0, 0, 0, 0, 0, 0, 0, 0],
            ],
            $this->transformer->transform($match)
        );
    }

    /**
     * Test the transformation when the match do not have moves.
     *
     * @test
     */
    public function transformMatchWithMoves()
    {
        $user = factory(\App\User::class)->create();
        $match = factory(\App\Match::class)->create();

        factory(\App\Move::class)->states('move-1', 'position-1')->create([
            'user_id' => $user->id,
            'match_id' => $match->id,
        ]);

        factory(\App\Move::class)->states('move-2', 'position-3')->create([
            'user_id' => $user->id,
            'match_id' => $match->id,
        ]);

        factory(\App\Move::class)->states('move-1', 'position-6')->create([
            'user_id' => $user->id,
            'match_id' => $match->id,
        ]);

        $this->assertEquals(
            [
                'id' => $match->id,
                'name' => 'Match'.$match->id,
                'next' => $match->next,
                'winner' => $match->winner,
                'combination' => $match->combination,
                'board' => [0, 1, 0, 2, 0, 0, 1, 0, 0],
            ],
            $this->transformer->transform($match)
        );
    }
}
