<?php

namespace App\Helpers;

use App\Models\Anime;
use App\Models\Review;

trait getOrFail
{
    public function getOrFailAnime(string|int $anime_id): Anime
    {
        $anime = Anime::where('id', $anime_id)->first();

        if (!$anime) {
            abort(404);
        }

        return $anime;
    }

    public function getOrFailReview(string|int $review_id): Review
    {
        $review = Review::where('id', $review_id)->first();

        if (!$review) {
            abort(404);
        }

        return $review;
    }
}
