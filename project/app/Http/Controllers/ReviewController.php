<?php

namespace App\Http\Controllers;

use App\Helpers\FilterHelper;
use App\Helpers\GetOrFail;
use App\Helpers\HasEnsure;
use App\Helpers\StringBuiler;
use App\Helpers\ToHTML;
use App\Models\Review;
use App\Models\ReviewUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReviewController extends Controller
{
    use GetOrFail;
    use ToHTML;
    use FilterHelper;
    use HasEnsure;
    use StringBuiler;


    public function index(string $title, int $production_year, int $id): View
    {
        $anime = $this->getOrFailAnime($id);

        $reviews = $this->filter(new Request(['anime_id' => $anime->id]));

        return view('animes.reviews.index')->with('reviews', $reviews)->with('anime', $anime);
    }

    public function show(string $title, int $production_year, int $id, int $review_id): View
    {
        $review_user = ReviewUsers::where('user_id', Auth::id())->where('review_id', $review_id)->first();
        $anime = $this->getOrFailAnime($id);

        $review = $this->getOrFailReview(strval($review_id));

        return view('animes.reviews.show')->with('review', $review)->with('anime', $anime)->with('review_user', $review_user);
    }

    public function create(string $title, int $production_year, int $id): View
    {
        $anime = $this->getOrFailAnime($id);

        return view('animes.reviews.create')->with('anime', $anime);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => ['exists:users,id', 'required',  'regex:/^[_a-z0-9 ]+$/i' ],
            'anime_id' => ['exists:animes,id', 'required',  'regex:/^[_a-z0-9 ]+$/i'],
            'title' => ['required', 'string'],
            'text' => ['required', 'min:256'],
        ]);

        /** @var string $anime_id */
        $anime_id = $request->anime_id;
        $anime = $this->getOrFailAnime($anime_id);

        $review = Review::create([
            'user_id' => $request->user_id,
            'anime_id' => $anime_id,
            'title' => $request->title,
            'text' => $request->text,
        ]);

        return redirect($this->reviewRedirect($anime, $review));
    }

    public function edit(string $title, int $production_year, int $id, int $review_id): View|RedirectResponse
    {
        $anime = $this->getOrFailAnime($id);

        $review = $this->getOrFailReview($review_id);

        if (Auth::id() != $review->user_id) {
            return back();
        }

        return view('animes.reviews.edit')->with('anime', $anime)->with("review", $review);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string'],
            'text' => ['required', 'min:500'],
            'review_id' => ['required', 'exists:reviews,id', 'integer'],
            'anime_id' => ['required', 'exists:animes,id', 'regex:/^[_a-z0-9 ]+$/i'],
        ]);

        /** @var string $review_id */
        $review_id = $request->review_id;

        /** @var string $anime_id */
        $anime_id = $request->anime_id;

        $review = $this->getOrFailReview($review_id);
        $anime = $this->getOrFailAnime($anime_id);

        if (Auth::id() != $review->user_id) {
            return back();
        }

        $review->title = $this->ensureIsString($request->title);
        $review->text = $this->ensureIsString($request->text);
        $review->save();

        return redirect($this->reviewRedirect($anime, $review));
    }

    public function destroy(string $title, string $production_year, string $id, int $review_id): RedirectResponse
    {
        $review = $this->getOrFailReview($review_id);

        $review->forceDelete();
        return redirect("/anime/" . $title ."-" . $production_year . "-" . $id);
    }

    public function filter(Request $request): Response
    {
        $request->validate([
            'anime_id' => ['exists:animes,id', 'required',  'regex:/^[_a-z0-9 ]+$/i']
        ]);

        $type = 'reviews';
        return $this->filterProcedure($request, $type);
    }
}
