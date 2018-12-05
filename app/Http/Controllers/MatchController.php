<?php

namespace App\Http\Controllers;

use App\Match;
use Illuminate\Support\Facades\Input;
use App\Transformer\MatchTransformer;
use League\Fractal\Manager  as TransformManager;
use League\Fractal\Resource\Collection as TransformCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MatchController extends Controller
{
    /**
     * @var \App\Transformer\MatchTransformer
     */
    private $transformer;

    public function __construct(MatchTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function index()
    {
        return view('index');
    }

    /**
     * Returns a list of matches
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function matches()
    {
        return response()->json($this->matchesTransformed()->toArray()["data"]);
    }

    /**
     * Returns the state of a single match
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function match($id)
    {
        $status = 200;
        $match = null;
        try {
            $match = $this->transformer->transform(Match::findOrFail($id));
        } catch (ModelNotFoundException $e) {
            $status = 404;
        }

        return response()->json($match, $status);
    }

    /**
     * Creates a new match and returns the new list of matches
     *
     * TODO it's mocked, make this work :)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        try {
            Match::create(['next' => random_int(1, 2), 'winner' => '0', 'combination' => '0']);
        } catch (\Exception $e) {

        }

        return response()->json($this->matchesTransformed()->toArray()["data"]);
    }

    /**
     * Deletes the match and returns the new list of matches
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        try {
            Match::findOrFail($id)->delete();
        } catch (ModelNotFoundException $e) {

        }
        return response()->json($this->matchesTransformed()->toArray()["data"]);
    }

    /**
     * Makes a move in a match
     *
     * TODO it's mocked, make this work :)
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function move($id) {
        $board = [
            1, 0, 2,
            0, 1, 2,
            0, 0, 0,
        ];

        $position = Input::get('position');
        $board[$position] = 2;

        return response()->json([
            'id' => $id,
            'name' => 'Match'.$id,
            'next' => 1,
            'winner' => 0,
            'board' => $board,
        ]);
    }

    private function matchesTransformed()
    {
        return (new TransformManager())->createData(new TransformCollection(Match::all(), $this->transformer));
    }

    /**
     * Creates a fake array of matches
     *
     * @return \Illuminate\Support\Collection
     */
    private function fakeMatches() {
        return collect([
            [
                'id' => 1,
                'name' => 'Match1',
                'next' => 2,
                'winner' => 1,
                'board' => [
                    1, 0, 2,
                    0, 1, 2,
                    0, 2, 1,
                ],
            ],
            [
                'id' => 2,
                'name' => 'Match2',
                'next' => 1,
                'winner' => 0,
                'board' => [
                    1, 0, 2,
                    0, 1, 2,
                    0, 0, 0,
                ],
            ],
            [
                'id' => 3,
                'name' => 'Match3',
                'next' => 1,
                'winner' => 0,
                'board' => [
                    1, 0, 2,
                    0, 1, 2,
                    0, 2, 0,
                ],
            ],
            [
                'id' => 4,
                'name' => 'Match4',
                'next' => 2,
                'winner' => 0,
                'board' => [
                    0, 0, 0,
                    0, 0, 0,
                    0, 0, 0,
                ],
            ],
        ]);
    }

}
