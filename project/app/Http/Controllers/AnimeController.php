<?php

namespace App\Http\Controllers;

use App\Helpers\toHTML;
use App\Models\Anime;
use App\Models\AnimeUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class AnimeController extends Controller
{
    use toHTML;

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
        $output = "";
        $filter = $request->filter ?? (session('anime_filter')?? "id");
        $filter_mode = $request->filter_mode ?? (session('anime_filter_mode') ?? "asc");
        $filter_genre = $request->filter_genre ?? (session('anime_filter_genre') ?? "all");
        $filter_search = $request->filter_search ?? (session('anime_filter_search') ?? "%");

        session(["anime_filter" => $filter, "anime_filter_mode" => $filter_mode, "anime_filter_genre" => $filter_genre, "anime_filter_search" => $filter_search]);

        if ($filter_genre == "all") {
            $filter_genre = '%';
        }

        $animes = Anime::where('title', 'like', '%' . $filter_search . "%")
            ->where('genre', 'like', '%'.$filter_genre.'%')
            ->orderBy(strval($filter), strval($filter_mode))
            ->get();
        if (count($animes) != 0) {
            foreach ($animes as $anime) {
                $output .= $this->animeToHTML($anime);
            }
            return Response($output);
        }
        return Response("<h2> There is not matching anime. </h2>");
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

        return redirect("/anime/$anime->title-$anime->production_year-$anime->id");
    }

    public function show(string $title, int $production_year, int $id): View
    {
        $anime = Anime::where('id', $id)->get();
        if (isset($anime[0])) {
            $anime = $anime[0];
        }

        $anime_user = "";

        $likes = array();

        if (Auth::id()) {
            $anime_user = AnimeUsers::where('user_id', Auth::id())->where('anime_id', $id)->first();
            $comments = DB::table('users')->join('comments', 'comments.author_id', '=', 'users.id')
                ->where('author_id', Auth::id())
                ->where('anime_id', $id)
                ->orderBy('comments.id', 'desc');
            $reviews = DB::table('users')->join('reviews', 'reviews.user_id', '=', 'users.id')
                ->where('user_id', Auth::id())
                ->where('anime_id', $id)
                ->orderBy('reviews.id', 'desc');


            if ($comments->count() < 5) {
                $subcomments = DB::table('users')->join('comments', 'comments.author_id', '=', 'users.id')
                                ->where('anime_id', $id)->whereNot(function ($query) {
                                    $query->where('author_id', Auth::id());
                                })->orderBy('comments.id', 'desc')
                                ->limit(5 - $comments->count());
                $comments = $comments->get()->concat($subcomments->get()->toArray());
            } else {
                $comments = $comments->get();
            }

            if ($reviews->count() < 3) {
                $subreviews = DB::table('users')->join('reviews', 'reviews.user_id', '=', 'users.id')
                                ->where('anime_id', $id)->whereNot(function ($query) {
                                    $query->where('user_id', Auth::id());
                                })->orderBy('rating', 'desc')
                                ->limit(3-$reviews->count());
                $reviews = $reviews->get()->concat($subreviews->get()->toArray());
            } else {
                $reviews= $reviews->get();
            }

            $likes = CommentController::likes_helper($comments);
        } else {
            $comments = DB::table('users')->join('comments', 'comments.author_id', '=', 'users.id')
                ->where('anime_id', $id)
                ->orderBy('comments.id', 'desc')
                ->limit(5)
                ->get();
            $reviews = DB::table('users')->join('reviews', 'reviews.user_id', '=', 'users.id')
                ->where('anime_id', $id)
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
            'id' => ['exists:animes', 'required'],
            'title' => ['required', 'string'],
            'genre' => ['required', 'string'],
            'production_year' => ['required', 'integer', 'numeric', 'digits:4'],
            'description' => ['nullable'],
            'poster' => ['string'],
            'episodes' => ['required', 'integer', 'min:1'],
        ]);

        if (!  file_exists($_SERVER['DOCUMENT_ROOT'] . "/images/" . $request->poster)) {
            $request->poster = "missing.jpg";
        }
        Anime::where('id', $request->id)
            ->update([
                'title' => $request->title,
                'genre' => $request->genre,
                'production_year' => $request->production_year,
                'poster' => $request->poster,
                'episodes' => $request->episodes,
                'description' => $request->description,
            ]);

        return redirect("/anime/" . strval($request->title) ."-" . strval($request->production_year) . "-" . strval($request->id));
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
}
