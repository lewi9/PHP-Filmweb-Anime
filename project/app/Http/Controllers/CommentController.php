<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

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
            'likes' => 0,
            'dislikes' => 0
            ]);

        return redirect("/anime/$request->title-$request->production_year-$request->anime_id");
    }

    public function update(Request $request)
    {
        $request->validate([
            'text' => ["string"]
        ]);
        Comment::where('id', $request->id)->update([
            'text' => $request->text
            ]);
    }

    public function destroy(Request $request)
    {
        if ($comments = Comment::where('id', $request->id)->get()) {
            foreach ($comments as $comment) {
                $comment->forceDelete();
            }
        }
    }

    public function like(Request $request): Response
    {
        $like = DB::table('likes_comments')
            ->where('user_id', $request->user_id)
            ->where('comment_id', $request->id)
            ->first();

        $comment = Comment::find($request->id);
        if (!$like) {
            DB::table('likes_comments')
                ->insert([
                'user_id' => $request->user_id,
                'comment_id' => $request->id,
                'rate' => 'like',
            ]);
            $comment->likes++;
            $comment->save();
        } else {
            if ($like->rate == "dislike") {
                $comment->dislikes--;
                $comment->likes++;
                $comment->save();
                $like->rate = 'like';
                $like->save();
            }
        }

        return Response($comment->likes . ',' . $comment->dislikes);
    }

    public function dislike(Request $request): Response
    {
        $like = DB::table('likes_comments')
            ->where('user_id', $request->user_id)
            ->where('comment_id', $request->id)
            ->first();

        $comment = Comment::find($request->id);
        if (!$like) {
            DB::table('likes_comments')
                ->insert([
                    'user_id' => $request->user_id,
                    'comment_id' => $request->id,
                    'rate' => 'dislike',
                ]);
            $comment->dislikes++;
            $comment->save();
        } else {
            if ($like->rate == "like") {
                $comment->dislikes++;
                $comment->likes--;
                $comment->save();
                $like->rate = 'dislike';
                $like->save();
            }
        }
        return Response($comment->likes . ',' . $comment->dislikes);
    }
}
