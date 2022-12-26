<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function show(Anime $anime): View
    {
        return View('animes.comments.show')->with('comments', DB::table('comments')
                ->join('users', 'comments.author_id', '=', 'users.id')
                ->where('anime_id', $anime->id)
                ->orderBy('comments.id', 'desc')
                ->get())->with('anime', $anime);
    }
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => ['required', 'exists:App\Models\User,id'],
            'text' => ['required', 'string'],
            'anime_id'=> ['required', "exists:App\Models\Anime,id"]
        ]);

        Comment::create([
            'author_id' => $request->user_id,
            'text' => $request->text,
            'anime_id' => $request->anime_id,
            ]);

        return back();
    }

    public function update(Request $request): void
    {
        $request->validate([
            'text' => ['required', "string"]
        ]);
        Comment::where('id', $request->id)->update([
            'text' => $request->text,
            'likes' => 0,
            'dislikes' => 0,
            ]);

        if (Auth::user()) {
            $like = DB::table('likes_comments')
                ->where('user_id', Auth::user()->id)
                ->where('comment_id', $request->id)
                ->delete();
        }
    }

    public function destroy(Request $request): void
    {
        $comments = Comment::where('id', $request->id)->get();
        foreach ($comments as $comment) {
            $comment->forceDelete();
        }
    }

    public function like(Request $request): Response
    {
        $request->validate([
            'id' => ['required', 'exists:App\Models\Comment,id'],
            'user_id'=> ['required', "exists:App\Models\User,id"],
            'status' => ['required'],
        ]);

        if ($request->status != "like" and $request->status != "dislike") {
            abort(404);
        }
        $like = DB::table('likes_comments')
            ->where('user_id', $request->user_id)
            ->where('comment_id', $request->id)
            ->first();

        $comment = Comment::where('id', $request->id)->first();
        if (!$comment) {
            abort(404);
        }

        if (!$like) {
            DB::table('likes_comments')
                ->insert([
                    'user_id' => $request->user_id,
                    'comment_id' => $request->id,
                    'rate' => $request->status,
                ]);
            if ($request->status == "like") {
                $comment->likes++;
            } else {
                $comment->dislikes++;
            }

            $comment->save();
        } else {
            if (isset($like->rate) and $like->rate != $request->status) {
                if ($request->status == "like") {
                    $comment->dislikes--;
                    $comment->likes++;
                } else {
                    $comment->dislikes++;
                    $comment->likes--;
                }

                $comment->save();
                DB::table('likes_comments')
                    ->where('user_id', $request->user_id)
                    ->where('comment_id', $request->id)
                    ->update(['rate' => $request->status]);
            }
        }
        return Response($comment->likes . ',' . $comment->dislikes);
    }
}
