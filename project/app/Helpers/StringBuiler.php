<?php

namespace App\Helpers;

use App\Models\Anime;
use App\Models\Review;

trait StringBuiler
{
    public function reviewRedirect(Anime $anime, Review $review): string
    {
        return "/anime/" . $anime->title ."-" . $anime->production_year . "-" . $anime->id . '/reviews/' . $review->id;
    }
}
