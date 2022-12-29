<?php

namespace App\Helpers;

use App\Models\Anime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait filterHelper
{
    public function filterProcedure(Request $request, string $type): Response
    {
        $anime = new Anime();
        if ($type != 'anime') {
            /** @var int|string $anime_id */
            $anime_id = $request->anime_id;
            $anime = $this->getOrFailAnime($anime_id);
            $collection = $this->filterHelper($request, $type, $anime->id);
        } else {
            $collection = $this->filterHelper($request, $type);
        }

        return match ($type) {
            'anime' => Response($this->animeCollectionToHTML($collection)),
            'comments' => Response($this->commentsCollectionToHTML($collection, $anime)),
            'reviews' => Response($this->reviewsCollectionToHTML($collection, $anime)),
            default => Response('<h2>Filter problem in filter procedure</h2>'),
        };
    }

    /**
     * @param Request $request
     * @param string $type
     * @param int|string $anime_id
     * @return Collection
     */
    private function filterHelper(Request $request, string $type, int|string $anime_id = ''): Collection
    {
        $filter = $request->filter ?? (session($type . '_filter')?? "id");
        $filter_mode = $request->filter_mode ?? (session($type. '_filter_mode') ?? "asc");

        session([$type . "_filter" => $filter, $type . "_filter_mode" => $filter_mode]);

        if ($type == 'anime') {
            $filter_genre = $request->filter_genre ?? (session($type . '_filter_genre') ?? "all");
            $filter_search = $request->filter_search ?? (session($type . '_filter_search') ?? "%");
            session([ $type . "_filter_genre" => $filter_genre, $type ."_filter_search" => $filter_search]);
            return $this->getAnimes(strval($filter), strval($filter_mode), strval($filter_genre), strval($filter_search));
        }

        if ($type == 'comments') {
            return $this->getComments(strval($filter), strval($filter_mode), $anime_id);
        }

        if ($type == 'reviews') {
            return $this->getReviews(strval($filter), strval($filter_mode), $anime_id);
        }

        return new Collection();
    }

    private function getComments(string $filter, string $filter_mode, string|int $anime_id): Collection
    {
        return DB::table('users')
            ->join('comments', 'comments.author_id', '=', 'users.id')
            ->where('anime_id', $anime_id)
            ->orderBy("comments." . $filter, $filter_mode)
            ->get();
    }

    private function getReviews(string $filter, string $filter_mode, string|int $anime_id): Collection
    {
        return DB::table('users')->join('reviews', 'reviews.user_id', '=', 'users.id')
            ->where('anime_id', $anime_id)
            ->orderBy('reviews.' . $filter, $filter_mode)
            ->get();
    }

    private function getAnimes(string $filter, string $filter_mode, string $filter_genre, string $filter_search): Collection
    {
        if ($filter_genre == "all") {
            $filter_genre = '%';
        }

        return Anime::where('title', 'like', '%' . $filter_search . "%")
            ->where('genre', 'like', '%'.$filter_genre.'%')
            ->orderBy($filter, $filter_mode)
            ->get();
    }
}