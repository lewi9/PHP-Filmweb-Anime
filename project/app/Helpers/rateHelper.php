<?php

namespace App\Helpers;

use App\Models\Anime;
use App\Models\AnimeUsers;
use App\Models\Review;
use App\Models\ReviewUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

trait rateHelper
{
    public function rateHelper(Anime|Review $element, AnimeUsers|ReviewUsers|null $relation, Request $request): Response
    {
        $element->cumulate_rating += $request->rating;
        if ($relation) {
            $element->cumulate_rating -= $relation->rating;
            if ($relation->rating == 0 and $request->rating != 0) {
                $element->rates++;
            }
            if ($request->rating == 0 and $relation->rating != 0) {
                $element->rates--;
            }
            if ($element->rates!=0) {
                $element->rating = $element->cumulate_rating/$element->rates;
            } else {
                $element->rating = 0;
            }
            $relation->rating = $request->rating;
            $element->save();
            $relation->save();

            return $this->createResponse($element);
        }
        if ($request->rating != 0) {
            $element->rates++;
            $element->rating = $element->cumulate_rating/$element->rates;
        }
        if ($element instanceof Anime) {
            $element->how_much_users_watched++;
            $this->createAnime($request, $element);
        }
        if ($element instanceof Review) {
            $this->createReview($request);
        }
        $element->save();

        return $this->createResponse($element);
    }

    private function createResponse(Anime|Review $element): Response
    {
        $output = "$element->rating, $element->rates, $element->cumulate_rating";
        if ($element instanceof Anime) {
            $output .= ", $element->how_much_users_watched";
        }
        return Response($output);
    }

    private function createAnime(Request $request, Anime $anime): void
    {
        AnimeUsers::create([
            'user_id' => $request->user_id,
            'anime_id' => $request->anime_id,
            'rating' => $request->rating,
            'watched' => true,
            "watched_episodes" => $anime->episodes,
        ]);
    }

    private function createReview(Request $request): void
    {
        ReviewUsers::create([
            'user_id' => $request->user_id,
            'review_id' => $request->review_id,
            'rating' => $request->rating,
        ]);
    }
}
