<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public static function review_helper(int $review_id): Review
    {
        $review = Review::where('id', $review_id)->first();

        if (!$review) {
            abort(404);
        }

        return $review;
    }

    public function index(string $title, int $production_year, int $id): View
    {
        $anime = AnimeController::anime_helper($id);

        $reviews = DB::table('users')->join('reviews', 'reviews.user_id', '=', 'users.id')
            ->where('anime_id', $anime->id)
            ->orderBy('rating', 'desc')
            ->get();

        return view('animes.reviews.index')->with('reviews', $reviews)->with('anime', $anime);
    }

    public function show(string $title, int $production_year, int $id, int $review_id): View
    {
        $anime = AnimeController::anime_helper($id);

        $review = self::review_helper($review_id);

        return view('animes.reviews.show')->with('review', $review)->with('anime', $anime);
    }

    public function create(string $title, int $production_year, int $id): View
    {
        $anime = AnimeController::anime_helper($id);

        return view('animes.reviews.create')->with('anime', $anime);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => ['exists:users,id', 'required'],
            'anime_id' => ['exists:animes,id', 'required'],
            'title' => ['required', 'string'],
            'text' => ['required', 'min:256'],
        ]);

        $review = Review::create([
            'user_id' => $request->user_id,
            'anime_id' => $request->anime_id,
            'title' => $request->title,
            'text' => $request->text,
        ]);

        return redirect("/anime/" . strval($request->anime_title) ."-" . strval($request->production_year) . "-" . strval($request->anime_id).'/reviews/' . $review->id);
    }

    public function edit(string $title, int $production_year, int $id, int $review_id): View|RedirectResponse
    {
        $anime = AnimeController::anime_helper($id);

        $review = self::review_helper($review_id);

        if (Auth::id() != $review->user_id) {
            return back();
        }

        return view('animes.reviews.edit')->with('anime', $anime)->with("review", $review);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string'],
            'text' => ['required', 'min:256'],
            'review_id' => ['required', 'exists:reviews,id', 'integer']
        ]);

        $review_id = intval($request->review_id);

        $review = self::review_helper($review_id);

        if (Auth::id() != $review->user_id) {
            return back();
        }

        $review->title = strval($request->title);
        $review->text = strval($request->text);
        $review->save();

        return redirect("/anime/" . strval($request->anime_title) ."-" . strval($request->production_year) . "-" . strval($request->anime_id).'/reviews/' . strval($review->id));
    }

    public function destroy(string $title, int $production_year, int $id, int $review_id): RedirectResponse
    {
        $review = self::review_helper($review_id);

        $review->forceDelete();
        return redirect("/anime/" . $title ."-" . strval($production_year) . "-" . strval($id));
    }
}
