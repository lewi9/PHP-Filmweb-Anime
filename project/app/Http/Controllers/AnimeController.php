<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnimeController extends Controller
{
    public function index(): View
    {
        return view('animes.index')->with('animes', Anime::all());
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
            //Dodać poster
            'description' => ['required', 'string'],
        ]);

        $anime = Anime::create([
            'title' => $request->title,
            'genre' => $request->genre,
            'production_year' => $request->production_year,
            'poster' => $request->poster?? "not_found",
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
            //Dodać poster
            'description' => ['required', 'string'],
        ]);

        Anime::where('id', $request->id)
            ->update([
                'title' => $request->title,
                'genre' => $request->genre,
                'production_year' => $request->production_year,
                'poster' => $request->poster?? "not_found",
                'description' => $request->description,
                'rating' => 0.0,
                'how_much_users_watched' => 0.0,
            ]);

        return redirect("/anime/$request->title-$request->production_year-$request->id");
    }

    public function destroy(anime $anime): RedirectResponse
    {
        $anime->forceDelete();
        return redirect('/anime');
    }
}
