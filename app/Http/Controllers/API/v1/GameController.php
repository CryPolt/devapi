<?php


namespace App\Http\Controllers\API\v1;


use App\Models\Game;
use App\Providers\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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


    public function createGame(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        $validatedData = $request->validate([
            'slug' => '',
            'title' => '',
            'description' => '',
            'thumbnail' => '',
            'upload_timestamp' => '',
            'score_count' => '',
            'popular' => '',
            'author' => ''

        ]);

        $user = Auth::guard('sanctum')->user();
        $user = User::all();
        $game = Game::create([
            'slug' => $validatedData['slug'],
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'thumbnail' => $validatedData['thumbnail'],
            'upload_timestamp' => $validatedData['upload_timestamp'],
            'author' => $validatedData['author'], // Assuming 'id' is the primary key of the user
            'score_count' => $validatedData['score_count'],
            'popular' => $validatedData['popular']
        ]);

        // Save the game
        $game->save();

        return response()->json(['status' => 'success', 'game' => $game]);
    }


}
