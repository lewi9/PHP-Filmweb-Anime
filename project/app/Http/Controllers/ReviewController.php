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
    public function index(string $title, int $production_year, int $id): View
    {
        $anime = Anime::where('id', $id)->first();

        if (!$anime) {
            abort(404);
        }

        $reviews = DB::table('users')->join('reviews', 'reviews.user_id', '=', 'users.id')
            ->where('anime_id', $anime->id)
            ->orderBy('rating', 'desc')
            ->limit(3)
            ->get();

        return view('animes.reviews.index')->with('reviews', $reviews)->with('anime', $anime);
    }

    public function show(string $title, int $production_year, int $id, int $review_id): View
    {
        $anime = Anime::where('id', $id)->first();
        $review = Review::where('id', $review_id)->first();

        if (!$anime or !$review) {
            abort(404);
        }

        return view('animes.reviews.show')->with('review', $review)->with('anime', $anime);
    }

    public function create(string $title, int $production_year, int $id): View
    {
        $anime = Anime::where('id', $id)->first();

        if (!$anime) {
            abort(404);
        }

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

        $title = $request->title;
        $production_year = $request->production_year;
        $id = $request->id;
        return redirect("/anime/" . strval($title) ."-" . strval($production_year) . "-" . strval($id).'/reviews.' . $review);
    }

    public function edit(string $title, int $production_year, int $id, int $review_id): View|RedirectResponse
    {
        $anime = Anime::where('id', $id)->first();
        $review = Review::where('id', $review_id)->first();

        if (!$anime or !$review) {
            abort(404);
        }

        if (Auth::id() != $review->user_id) {
            return back();
        }


        if (!$anime) {
            abort(404);
        }

        return view('animes.reviews.edit')->with('anime', $anime)->with("review", $review);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string'],
            'text' => ['required', 'min:256'],
            'review_id' => ['required', 'exists:reviews,id']
        ]);
        $id = $request->id;

        $review_id = strval($request->review->id);
        $review = Review::where('id', $review_id)->first();

        if (Auth::id() != $review->user_id) {
            return back();
        }

        $review->title = $request->title;
        $review->text = $request->text;
        $review->save();

        $title = $request->title;
        $production_year = $request->production_year;

        return redirect("/anime/" . strval($title) ."-" . strval($production_year) . "-" . strval($id).'/reviews.' . $review);
    }

    public function destroy(int $review_id): RedirectResponse
    {
        $review = Review::where('id', $review_id)->first();

        if (!$review) {
            abort(404);
        }

        if (Auth::id() != $review->user_id) {
            return back();
        }

        $review->forceDelete();
        return back();
    }
}
