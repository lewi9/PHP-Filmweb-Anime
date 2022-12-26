<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use App\Models\AnimeUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AnimeUsersController extends Controller
{
    public function manage_list(Request $request): Response
    {
        $request->validate([
            "anime_id" => ['required', 'exists:animes,id'],
            'user_id' => ['required', 'exists:users,id'],
            'list' => ["required"],
        ]);

        if ($request->list == "favorite") {
            $favorite = true;
            $to_watch = false;
        } elseif ($request->list == "to_watch") {
            $favorite = false;
            $to_watch = true;
        } else {
            abort(404);
        }

        $anime_user = AnimeUsers::where('anime_id', $request->anime_id)->where('user_id', $request->user_id)->first();

        if ($favorite and $anime_user) {
            return $this->favorite($anime_user, $request);
        }

        if ($to_watch and $anime_user) {
            return $this->to_watch($anime_user, $request);
        }

        AnimeUsers::create([
            'user_id' => $request->user_id,
            'anime_id' => $request->anime_id,
            'would_like_to_watch' => false,
            'favorite' => $favorite,
            'rating' => '0',
            'watched' => $to_watch,
        ]);

        return Response("added");
    }

    //To deal with copy detector

    private function favorite(AnimeUsers $anime_user, Request $request): Response
    {
        if ($anime_user->favorite) {
            $anime_user->favorite = false;
            $anime_user->save();
            return Response("removed");
        }
        $anime_user->favorite = true;
        $anime_user->save();
        return Response("added");
    }

    private function to_watch(AnimeUsers $anime_user, Request $request): Response
    {
        if ($anime_user->would_like_to_watch) {
            $anime_user->would_like_to_watch = false;
            $anime_user->save();
            return Response("removed");
        }
        $anime_user->would_like_to_watch = true;
        $anime_user->save();
        return Response("added");
    }

    public function rate(Request $request): Response
    {
        $request->validate([
            "anime_id" => ['required', 'exists:animes,id'],
            'user_id' => ['required', 'exists:users,id'],
            'rating' => ['required', 'integer', 'min:0', 'max:10']
        ]);
        $anime_user = AnimeUsers::where('anime_id', $request->anime_id)->where('user_id', $request->user_id)->first();
        $anime = Anime::where('id', $request->anime_id)->first();
        if (!$anime) {
            abort(404);
        }
        $anime->cumulate_rating += intval($request->rating);
        if ($anime_user) {
            $anime->cumulate_rating -= intval($anime_user->rating);
            if (intval($anime_user->rating) == 0 and intval($request->rating) != 0) {
                $anime->rates++;
            }
            if (intval($request->rating) == 0 and intval($anime_user->rating) != 0) {
                $anime->rates--;
            }
            if ($anime->rates!=0) {
                $anime->rating = $anime->cumulate_rating/$anime->rates;
            } else {
                $anime->rating = 0;
            }
            $anime_user->rating = strval($request->rating);
            $anime_user->save();
            $anime->save();
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
