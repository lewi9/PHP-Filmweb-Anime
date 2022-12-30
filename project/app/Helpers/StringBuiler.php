<?php

namespace App\Helpers;

use App\Models\Anime;
use App\Models\Review;

trait StringBuiler
{
    public function reviewRedirect(Anime $anime, Review $review): string
    {
        return $this->animeRedirect($anime) . '/reviews/' . $review->id;
    }

    public function animeRedirect(Anime $anime): string
    {
        return "/anime/$anime->title-$anime->production_year-$anime->id";
    }
}
