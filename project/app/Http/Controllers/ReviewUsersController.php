<?php

namespace App\Http\Controllers;

use App\Helpers\getOrFail;
use App\Models\ReviewUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReviewUsersController extends Controller
{
    use getOrFail;

    public function rate(Request $request): Response
    {
        $request->validate([
            'review_id' => ['required', 'exists:reviews,id'],
            'user_id' => ['required', 'exists:users,id'],
            'rating' => ['required', 'integer', 'min:0', 'max:10']
        ]);

        $review_user = ReviewUsers::where('review_id', $request->review_id)->where('user_id', $request->user_id)->first();
        $review = $this->getOrFailReview($request->review_id);

        $review->cumulate_rating += intval($request->rating);

        if ($review_user) {
            $review->cumulate_rating -= intval($review_user->rating);
            if (intval($review_user->rating) == 0 and intval($request->rating) != 0) {
                $review->rates++;
            }
            if (intval($request->rating) == 0 and intval($review_user->rating) != 0) {
                $review->rates--;
            }
            if ($review->rates!=0) {
                $review->rating = $review->cumulate_rating/$review->rates;
            } else {
                $review->rating = 0;
            }
            $review_user->rating = intval($request->rating);
            $review_user->save();
            $review->save();
            return Response("$review->rating, $review->rates, $review->cumulate_rating");
        }
        if (intval($request->rating) != 0) {
            $review->rates++;
            $review->rating = $review->cumulate_rating/$review->rates;
        }

        $review->save();
        ReviewUsers::create([
            'user_id' => $request->user_id,
            'review_id' => $request->review_id,
            'rating' => $request->rating,
        ]);
        return Response("$review->rating, $review->rates, $review->cumulate_rating");
    }
}
