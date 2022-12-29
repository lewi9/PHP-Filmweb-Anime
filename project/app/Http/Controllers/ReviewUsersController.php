<?php

namespace App\Http\Controllers;

use App\Helpers\getOrFail;
use App\Helpers\rateHelper;
use App\Models\ReviewUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReviewUsersController extends Controller
{
    use getOrFail;
    use rateHelper;

    public function rate(Request $request): Response
    {
        $request->validate([
            'review_id' => ['required', 'exists:reviews,id'],
            'user_id' => ['required', 'exists:users,id'],
            'rating' => ['required', 'integer', 'min:0', 'max:10']
        ]);

        $review_user = ReviewUsers::where('review_id', $request->review_id)->where('user_id', $request->user_id)->first();
        $review = $this->getOrFailReview($request->review_id);

        return $this->rateHelper($review, $review_user, $request);
    }
}
