<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => ['exists:App\Models\User,id'],
            'text' => ['string'],
            'anime_id'=> ["exists:App\Models\Anime,id"]
        ]);

        Comment::create([
            'author_id' => $request->user_id,
            'text' => $request->text,
            'anime_id' => $request->anime_id,
            ]);

        return redirect("/anime/$request->title-$request->production_year-$request->anime_id");
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'text' => ["string"]
        ]);

        Comment::where('id', $request->c_id)->update([
            'text' => $request->text
            ]);

        return redirect("/anime/$request->title-$request->production_year-$request->id");
    }

    public function destroy(Request $request): RedirectResponse
    {
        if ($comments = Comment::where('id', $request->c_id)->get()) {
            foreach($comments as $comment) {
                $comment->forceDelete();
            }
        }
        return redirect("/anime/$request->title-$request->production_year-$request->id");
    }
}
