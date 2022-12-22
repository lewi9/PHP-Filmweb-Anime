<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnimeController extends Controller
{
    public function index(Request $request): View
    {
        $genres = array();
        $raw_genres = Anime::select('genre')->groupBy('genre')->get();
        foreach ($raw_genres as $genre) {
            $raw_genre = explode(":", $genre)[1];
            $raw_genre = strtolower(str_replace(["\"", "}"], "", $raw_genre));
            $raw_genre = explode(",", $raw_genre);
            if (is_array($raw_genre)) {
                foreach ($raw_genre as $hraw_genre) {
                    $genres[] = trim($hraw_genre);
                }
            } else {
                $genres[] = trim($raw_genre);
            }
        }
        $genres = array_unique($genres);

        $filter = $request->filter ?? (session('filter')?? "id");
        $filter_mode = $request->filter_mode ?? (session('filter_mode') ?? "asc");
        $filter_genre = $request->filter_genre ?? (session('filter_genre') ?? "all");
        session(["filter" => $filter, "filter_mode" => $filter_mode, "filter_genre" => $filter_genre]);

        if ($filter_genre == "all") {
            $filter_genre = "%";
        }
        return view('animes.index')->with('animes', Anime::where('genre', 'like', '%'.$filter_genre.'%')
            ->orderBy($filter, $filter_mode)->get())->with('genres', $genres);
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
            'description' => ['nullable'],
        ]);

        if ($request->poster != null) {
            $request->validate(['poster' => ['image','mimes:png,jpg,jpeg','max:2048']]);
            $imageName = $request->title . $request->production_year. rand(0, 10) . "." . $request->poster->extension();
            $request->poster->move(public_path('images'), $imageName);
        } else {
            $imageName = "missing.jpg";
        }


        $anime = Anime::create([
            'title' => $request->title,
            'genre' => $request->genre,
            'production_year' => $request->production_year,
            'poster' => $imageName,
            'description' => $request->description,
            'rating' => 0.0,
            'how_much_users_watched' => 0.0,

        ]);

        return redirect("/anime/$anime->title-$anime->production_year-$anime->id");
    }

    public function show(string $title, int $production_year, int $id): View
    {
        return view('animes.show')->with('anime', Anime::where('id', $id)->get()[0]);
    }

    public function edit(anime $anime): View
    {
        return view('animes.edit')->with('anime', $anime);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string'],
            'genre' => ['required', 'string'],
            'production_year' => ['required', 'integer', 'numeric', 'digits:4'],
            'description' => ['nullable'],
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
                'description' => $request->description,
                'rating' => 0.0,
                'how_much_users_watched' => 0.0,
            ]);

        return redirect("/anime/$request->title-$request->production_year-$request->id");
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
