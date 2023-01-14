<?php

namespace App\Http\Controllers;

use App\Helpers\FilterHelper;
use App\Helpers\GetOrFail;
use App\Helpers\LikesHelper;
use App\Helpers\StringBuiler;
use App\Helpers\ToHTML;
use App\Models\Anime;
use App\Models\AnimeUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use mysql_xdevapi\Collection;

class AnimeController extends Controller
{
    use GetOrFail;
    use ToHTML;
    use FilterHelper;
    use LikesHelper;
    use StringBuiler;

    public function index(): View
    {
        $genres = array();
        $raw_genres = Anime::select('genre')->groupBy('genre')->get();
        foreach ($raw_genres as $genre) {
            $raw_genre = explode(":", $genre)[1];
            $raw_genre = strtolower(str_replace(["\"", "}"], "", $raw_genre));
            $raw_genre = explode(",", $raw_genre);
            foreach ($raw_genre as $hraw_genre) {
                $genres[] = trim($hraw_genre);
            }
        }
        $genres = array_unique($genres);

        return view('animes.index')->with('animes', $this->filter(new Request()))->with('genres', $genres);
    }

    public function filter(Request $request): Response
    {
        $request->validate([
            'filter' => ['string'],
            'filter_mode' => ['string'],
            'filter_genre' => ['string'],
            'filter_search' => ['string'],
        ]);

        $type = 'anime';
        return $this->filterProcedure($request, $type);
    }


    public function create(): View
    {
        return view('animes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string'],
            'genre' => ['required', 'string'],
            'production_year' => ['required', 'integer', 'numeric', 'digits:4'],
            'description' => ['nullable', 'string'],
            'episodes' => ['required', 'integer', 'min:1'],
        ]);

        if ($request->poster != null) {
            $request->validate(['poster' => ['image','mimes:png,jpg,jpeg','max:2048']]);

            /** @var \Illuminate\Http\UploadedFile $file */
            $file = $request->poster;

            $imageName = $request->title . $request->production_year. rand(0, 10) . "." . $file->extension();
            $file->move(public_path('images'), $imageName);
        } else {
            $imageName = "missing.jpg";
        }


        $anime = Anime::create([
            'title' => $request->title,
            'genre' => $request->genre,
            'production_year' => $request->production_year,
            'poster' => $imageName,
            'episodes' => $request->episodes,
            'description' => $request->description,
            'rating' => 0.0,
            'how_much_users_watched' => 0.0,
            'rates' => 0,
            "cumulate_rating" => 0
        ]);

        return redirect($this->animeRedirect($anime));
    }

    public function show(string $anime_title, int $anime_production_year, int $anime_id): View
    {
        $anime = $this->getOrFailAnime($anime_id);

        $anime_user = "";

        $likes = array();

        if (Auth::id()) {
            $anime_user = AnimeUsers::where('user_id', Auth::id())->where('anime_id', $anime_id)->first();
            $comments = DB::table('users')->join('comments', 'comments.user_id', '=', 'users.id')
                ->where('user_id', Auth::id())
                ->where('anime_id', $anime_id)
                ->orderBy('comments.id', 'desc');
            $reviews = DB::table('users')->join('reviews', 'reviews.user_id', '=', 'users.id')
                ->where('user_id', Auth::id())
                ->where('anime_id', $anime_id)
                ->orderBy('reviews.id', 'desc');


            if ($comments->count() < 5) {
                $subcomments = DB::table('users')->join('comments', 'comments.user_id', '=', 'users.id')
                                ->where('anime_id', $anime_id)->whereNot(function ($query) {
                                    $query->where('user_id', Auth::id());
                                })->orderBy('comments.id', 'desc')
                                ->limit(5 - $comments->count());
                $comments = $comments->get()->concat($subcomments->get()->toArray());
            } else {
                $comments = $comments->get();
            }

            if ($reviews->count() < 3) {
                $subreviews = DB::table('users')->join('reviews', 'reviews.user_id', '=', 'users.id')
                                ->where('anime_id', $anime_id)->whereNot(function ($query) {
                                    $query->where('user_id', Auth::id());
                                })->orderBy('rating', 'desc')
                                ->limit(3-$reviews->count());
                $reviews = $reviews->get()->concat($subreviews->get()->toArray());
            } else {
                $reviews= $reviews->get();
            }

            $likes = $this->likesHelper($comments);
        } else {
            $comments = DB::table('users')->join('comments', 'comments.user_id', '=', 'users.id')
                ->where('anime_id', $anime_id)
                ->orderBy('comments.id', 'desc')
                ->limit(5)
                ->get();
            $reviews = DB::table('users')->join('reviews', 'reviews.user_id', '=', 'users.id')
                ->where('anime_id', $anime_id)
                ->orderBy('rating', 'desc')
                ->limit(3)
                ->get();
        }


        return view('animes.show')->with('anime', $anime)->with('comments', $comments)
            ->with('anime_user', $anime_user)->with('comment_like', $likes)->with('reviews', $reviews);
    }

    public function edit(anime $anime): View
    {
        return view('animes.edit')->with('anime', $anime);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'id' => ['exists:animes', 'required', 'regex:/^[_a-z0-9 ]+$/i'],
            'title' => ['required', 'string'],
            'genre' => ['required', 'string'],
            'production_year' => ['required', 'integer', 'numeric', 'digits:4'],
            'description' => ['nullable', 'string'],
            'poster' => ['string'],
            'episodes' => ['required', 'integer', 'min:1'],
        ]);

        if (!  file_exists($_SERVER['DOCUMENT_ROOT'] . "/images/" . $request->poster)) {
            $request->poster = "missing.jpg";
        }

        /** @var string $anime_id */
        $anime_id = $request->id;
        $anime = $this->getOrFailAnime($anime_id);

        /** @var string $title */
        $title = $request->title;
        $anime->title = $title;

        /** @var string $genre */
        $genre = $request->genre;
        $anime->genre = $genre;

        /** @var integer $production_year */
        $production_year = $request->production_year;
        $anime->production_year = $production_year;

        /** @var string $poster */
        $poster = $request->poster;
        $anime->poster = $poster;

        /** @var integer $episodes */
        $episodes = $request->episodes;
        $anime->episodes = $episodes;

        /** @var string $description */
        $description = $request->description;
        $anime->description = $description;

        $anime->save();

        return redirect($this->animeRedirect($anime));
    }

    public function destroy(anime $anime): RedirectResponse
    {
        if ($anime->poster != "missing.jpg") {
            $image_path = "/images/$anime->poster";
            unlink($_SERVER['DOCUMENT_ROOT'] . $image_path);
        }

        $anime->forceDelete();
        return redirect('/anime');
    }

    public function get_ratings(): View
    {
        $animes = Anime::all();
        return view('animes.ratings')->with('animes', $animes->sortByDesc('rating'));
    }

    public function calculate_ratings(Request $request): Response
    {
        return $this->filterProcedure($request, 'rating');
    }
}
