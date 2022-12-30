<?php

namespace App\Helpers;

use App\Models\Anime;
use App\Models\AnimeUsers;
use Illuminate\Support\Collection;

trait GetForUsers
{
    /**
     * @param int $user_id
     * @param string $list
     * @return array<Anime>
     */
    public function getListAnimes(int $user_id, string $list): array
    {
        $user_animes = AnimeUsers::where('user_id', $user_id)->where($list, true)->get();
        $anime_list = [];
        foreach ($user_animes as $to_list) {
            $anime_id = $to_list->anime_id;
            $anime_list[] = $this->getOrFailAnime($anime_id);
        }
        return $anime_list;
    }

    /**
     * @param int $user_id
     * @param string $for
     * @return array<mixed>
     */
    public function getForAnimes(int $user_id, string $for): array
    {
        $user_animes = new Collection();
        if ($for == 'ratings') {
            $user_animes = AnimeUsers::where('user_id', $user_id)->where('rating', '!=', '0')->get();
        }
        if ($for == 'watched_episodes') {
            $user_animes = AnimeUsers::where('user_id', $user_id)->where('watched', true)->get();
        }
        $anime_list = [];
        foreach ($user_animes as $user_anime) {
            $anime_id = $user_anime->anime_id;
            if ($for == 'ratings') {
                $anime_list[] = array($this->getOrFailAnime($anime_id), $user_anime->rating);
            }
            if ($for == 'watched_episodes') {
                $anime_list[] = array($this->getOrFailAnime($anime_id), $user_anime->watched_episodes);
            }
        }
        return $anime_list;
    }
}
