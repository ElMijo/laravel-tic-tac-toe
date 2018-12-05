<?php

namespace App\Http\Controllers;

use App\Move;
use App\Match;
use Illuminate\Support\Facades\Input;
use App\Services\MatchService;
use App\Transformer\MatchTransformer;
use League\Fractal\Manager  as TransformManager;
use League\Fractal\Resource\Collection as TransformCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\MatchException;

class MatchController extends Controller
{
    /**
     * @var \App\Transformer\MatchTransformer
     */
    private $transformer;

    /**
     * @var \App\Services\MatchService
     */
    private $service;

    public function __construct(MatchTransformer $transformer, MatchService $service)
    {
        $this->transformer = $transformer;
        $this->service = $service;
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
            Match::create(['next' => "1", 'winner' => '0', 'combination' => '0']);
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
            $match = Match::findOrFail($id);
            Move::where("match_id", "=", $id)->delete();
            $match->delete();
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
    public function move($id)
    {
        $status = 200;
        $result = [];
        try {
            $user = Auth::user();
            $match = Match::findOrFail($id);
            $position = strval(Input::get('position'));

            if($this->service->isGameOver($match)) {
                throw new MatchException("The game is over", 400);
            }

            if (!$this->service->isPositionAvailable($match, $position)) {
                throw new MatchException("The position was already played", 400);
            }

            if(!$this->service->isUserTurn($match, $user)) {
                throw new MatchException("it's not your turn", 400);
            }

            $move = $this->service->getUserMove($match, $user);

            Move::create([
                "move" => $move,
                "position" => $position,
                "user_id" => $user->id,
                "match_id" => $match->id
            ]);

            $this->service->processWinner($match);
            $match->switchNext()->save();

            $result = $this->transformer->transform($match);
        } catch (ModelNotFoundException $e) {
            $status = 404;
            $result["message"] = "The  match [$id] not found";
        } catch (MatchException $e) {
            $status = $e->getCode();
            $result["message"] = $e->getMessage();
        }

        return response()->json($result, $status);
    }

    private function matchesTransformed()
    {
        return (new TransformManager())->createData(new TransformCollection(Match::all(), $this->transformer));
    }
}
