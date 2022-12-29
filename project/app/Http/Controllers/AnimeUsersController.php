<?php

namespace App\Http\Controllers;

use App\Helpers\getOrFail;
use App\Helpers\rateHelper;
use App\Models\Anime;
use App\Models\AnimeUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AnimeUsersController extends Controller
{
    use getOrFail;
    use rateHelper;
    public function manage_list(Request $request): Response
    {
        $request->validate([
            "anime_id" => ['required', 'exists:animes,id', 'regex:/^[a-z0-9 ]+$/i'],
            'user_id' => ['required', 'exists:users,id', 'regex:/^[a-z0-9 ]+$/i'],
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
            'would_like_to_watch' => $to_watch,
            'favorite' => $favorite,
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
            "anime_id" => ['required', 'exists:animes,id', 'regex:/^[a-z0-9 ]+$/i'],
            'user_id' => ['required', 'exists:users,id', 'regex:/^[a-z0-9 ]+$/i'],
            'rating' => ['required', 'integer', 'min:0', 'max:10']
        ]);
        $anime_user = AnimeUsers::where('anime_id', $request->anime_id)->where('user_id', $request->user_id)->first();
        $anime = $this->getOrFailAnime($request->anime_id);

        return $this->rateHelper($anime, $anime_user, $request);
    }

    public function watched_episodes(Request $request): Response
    {
        $request->validate([
            "anime_id" => ['required', 'exists:animes,id', 'regex:/^[a-z0-9 ]+$/i'],
            'user_id' => ['required', 'exists:users,id', 'regex:/^[a-z0-9 ]+$/i'],
        ]);

        $anime = Anime::where('id', $request->anime_id)->first();
        if (!$anime) {
            abort(404);
        }
        $request->validate([
            "episodes" => ["integer", "min:0", 'required']
        ]);

        $anime_user = AnimeUsers::where('anime_id', $request->anime_id)->where('user_id', $request->user_id)->first();
        $flag = false;
        if ($request->episodes >= $anime->episodes) {
            $request->episodes = $anime->episodes;
            $flag = true;
        }

        if ($anime_user) {
            $anime_user->watched_episodes = intval($request->episodes);
            $anime_user->save();
            return Response(strval($request->episodes));
        }

        AnimeUsers::create([
            'user_id' => $request->user_id,
            'anime_id' => $request->anime_id,
            'watched' => $flag,
            'watched_episodes' => intval($request->episodes),
        ]);

        return Response(strval($request->episodes));
    }
}
