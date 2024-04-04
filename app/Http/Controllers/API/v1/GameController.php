<?php


namespace App\Http\Controllers\API\v1;


use App\Models\Game;
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
        $user = auth()->user();
        $user = Auth::guard('sanctum')->user();
        if ($user) {
            // Создаем новую игру, привязывая ее к пользователю
            $game = new Game();
            $game->author = $user->id; // Привязываем игру к пользователю
            $game->slug = $request->input('slug'); // Заполняем остальные данные игры
            $game->description = $request->input('description');
            $game->title = $request->input('title');
            $game->thumbnail = $request->input('thumbnail');
            $uploadTimestamp = $request->get('uploadTimestamp');
            $game->score_count = $request->input("score_count");
            $game->save();

            return response()->json(['message' => 'Game created successfully'], 201);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }


    }

}
