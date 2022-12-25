<?php

namespace App\Http\Controllers;

use App\Models\AnimeUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AnimeUsersController extends Controller
{
    public function favorite(Request $request): Response
    {
        $anime_user = AnimeUsers::where('anime_id', $request->anime_id)->where('user_id', $request->user_id)->first();
        if ($anime_user) {
            if ($anime_user->favorite) {
                $anime_user->favorite = false;
                $anime_user->save();
                return Response("removed");
            }
            $anime_user->favorite = true;
            $anime_user->save();
            return Response("added");
        }
        AnimeUsers::create([
            'user_id' => $request->user_id,
            'anime_id' => $request->anime_id,
            'would_like_to_watch' => false,
            'favorite' => true,
            'rating' => '0',
        ]);
        return Response("added");
    }

    public function to_watch(Request $request): Response
    {
        $anime_user = AnimeUsers::where('anime_id', $request->anime_id)->where('user_id', $request->user_id)->first();
        if ($anime_user) {
            if ($anime_user->would_like_to_watch) {
                $anime_user->would_like_to_watch = false;
                $anime_user->save();
                return Response("removed");
            }
            $anime_user->would_like_to_watch = true;
            $anime_user->save();
            return Response("added");
        }
        AnimeUsers::create([
            'user_id' => $request->user_id,
            'anime_id' => $request->anime_id,
            'would_like_to_watch' => true,
            'favorite' => false,
            'rating' => '0',
        ]);
        return Response("added");
    }
}
