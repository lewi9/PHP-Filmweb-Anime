<?php

namespace App\Http\Controllers;

use App\Models\Anime;
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
            'watched' => false,
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
            'watched' => false,
        ]);
        return Response("added");
    }

    public function rate(Request $request): Response
    {
        $anime_user = AnimeUsers::where('anime_id', $request->anime_id)->where('user_id', $request->user_id)->first();
        $anime = Anime::find($request->anime_id);
        $anime->cumulate_rating += intval($request->rating);
        if ($anime_user) {
            $anime->cumulate_rating -= intval($anime_user->rating);
            if(intval($anime_user->rating) == 0 and intval($request->rating) != 0) {
                $anime->rates++;
            }
            if (intval($request->rating) == 0 and intval($anime_user->rating) != 0) {
                $anime->rates--;
            }
            if($anime->rates!=0) {
                $anime->rating = $anime->cumulate_rating/$anime->rates;
            } else {
                $anime->rating = 0;
            }

            $anime->save();
            $anime_user->rating = $request->rating;
            $anime_user->save();
            return Response("$anime->rating, $anime->how_much_users_watched, $anime->rates, $anime->cumulate_rating");
        }
        if (intval($request->rating) != 0) {
            $anime->rates++;
            $anime->rating = $anime->cumulate_rating/$anime->rates;
        }
        $anime->how_much_users_watched++;
        $anime->save();
        AnimeUsers::create([
            'user_id' => $request->user_id,
            'anime_id' => $request->anime_id,
            'would_like_to_watch' => false,
            'favorite' => false,
            'rating' => $request->rating,
            'watched' => true,
        ]);
        return Response("$anime->rating, $anime->how_much_users_watched, $anime->rates, $anime->cumulate_rating");
    }
}
