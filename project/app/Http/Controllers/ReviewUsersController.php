<?php

namespace App\Http\Controllers;

use App\Helpers\GetOrFail;
use App\Helpers\RateHelper;
use App\Models\ReviewUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReviewUsersController extends Controller
{
    use GetOrFail;
    use RateHelper;

    public function rate(Request $request): Response
    {
        $request->validate([
            'review_id' => ['required', 'exists:reviews,id',  'regex:/^[_a-z0-9 ]+$/i'],
            'user_id' => ['required', 'exists:users,id',  'regex:/^[_a-z0-9 ]+$/i'],
            'rating' => ['required', 'integer', 'min:0', 'max:10']
        ]);

        /** @var string $review_id */
        $review_id = $request->review_id;

        $review_user = ReviewUsers::where('review_id', $review_id)->where('user_id', $request->user_id)->first();
        $review = $this->getOrFailReview($review_id);

        return $this->rateHelper($review, $review_user, $request);
    }
}
