<?php


namespace App\Http\Controllers\API\v1;


use App\Models\Game;
use App\Providers\User;
use Illuminate\Http\Request;

class GameController
{
    public function index()
    {
        $games = Game::all();
        $response = ['content'];
        foreach ($games as $game) {
            $response[$game->title] = [
                'slug' => $game->slug,
                'title' => $game->title,
                'description' => $game->description,
                'thumbnail' => $game->thumbnail,
                'uploadTimestamp' => $game->uploadTimestamp,
                'author' => $game->author,
                'scoreCount' => $game->scoreCount,
            ];
        }
        return response()->json($response,200);
    }

    public function show($id)
    {
        $game = Game::findOrFail($id);

        $response[$id] = [
            'slug' => $game->slug,
            'title' => $game->title,
            'description' => $game->description,
            'thumbnail' => $game->thumbnail,
            'uploadTimestamp' => $game->uploadTimestamp,
            'author' => $game->author,
            'scoreCount' => $game->scoreCount,
        ];

        return response()->json($response, 200);
}
    public function destroy($id)
    {
        $game = Game::find($id);

        if (!$game) {
            return response()->json(['message' => 'not found'], 404);
        }
        $game->delete();
        return response()->json([], 204);
    }

    public function update(Request $request, $id) {
        $game = Game::find($id);

        if (!$game) {
            return response()->json([
                'status' => 'error',
                'message' => 'Game not found'
            ], 404);
        }
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $game->fill($validatedData);

        try {
            $game->save();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'forbidden',
                'message' => 'You are not the game author' . $e->getMessage()
            ], 403);
        }

        return response()->json([
            'status' => 'success',
        ], 200);
    }

    public function createGame(Request $request) {
        // Ensure that the user making the request is authenticated
        $user = $request->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not authenticated'], 401);
        }

        // Validate the request data
        $validatedData = $request->validate([
            'slug' => '',
            'title' => '',
            'description' => '',
            'thumbnail' => '',
            'upload_timestamp' => '',
            'author' => '',
            'score_count' => '',
            'popular' => ''
            // Add more validation rules as needed
        ]);

        // Create a new game associated with the authenticated user
        $game = new Game($validatedData);
        $game->user_id = $user->id; // Assign the authenticated user's ID
        $game->save();

        return response()->json(['status' => 'success', 'game' => $game]);
    }       


}
