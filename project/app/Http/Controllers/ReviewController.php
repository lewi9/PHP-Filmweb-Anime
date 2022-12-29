<?php

namespace App\Http\Controllers;

use App\Helpers\getOrFail;
use App\Helpers\toHTML;
use App\Models\Review;
use App\Models\ReviewUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use stdClass;

class ReviewController extends Controller
{
    use getOrFail;
    use toHTML;



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
            'review_id' => ['required', 'exists:reviews,id', 'integer']
        ]);

        $review_id = $request->review_id;

        $review = $this->getOrFailReview($review_id);

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
        $review = $this->getOrFailReview($review_id);

        $review->forceDelete();
        return redirect("/anime/" . $title ."-" . strval($production_year) . "-" . strval($id));
    }

    public function filter(Request $request): Response
    {
        $request->validate([
            'anime_id' => ['exists:animes,id', 'required']
        ]);

        $anime = $this->getOrFailAnime($request->anime_id);

        $output = "";
        $filter = $request->filter ?? (session('reviews_filter')?? "id");
        $filter_mode = $request->filter_mode ?? (session('reviews_filter_mode') ?? "asc");

        session(["reviews_filter" => $filter, "reviews_filter_mode" => $filter_mode]);

        $reviews = DB::table('users')->join('reviews', 'reviews.user_id', '=', 'users.id')
            ->where('anime_id', $anime->id)
            ->orderBy('reviews.'.strval($filter), strval($filter_mode))
            ->get();

        if (count($reviews) > 0) {
            foreach ($reviews as $review) {
                /** @var stdClass $review */
                $output .= $this->reviewToHTML($review, $anime);
            }
            return Response($output);
        }
        return Response("<h2> There is no matching Review. </h2>");
    }
}
