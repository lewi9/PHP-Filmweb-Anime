<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use stdClass;

class CommentController extends Controller
{
    /**
     * @param Collection $comments
     * @return array<int, object|null>
     */
    public static function likes_helper(Collection $comments): array
    {
        $likes = array();
        foreach ($comments as $comment) {
            if ($comment instanceof stdClass) {
                $likes[] = DB::table('likes_comments')
                    ->where('comment_id', $comment->id)
                    ->where('user_id', Auth::id())->first();
            }
        }
        return $likes;
    }

    public function show(Anime $anime): View
    {
        $likes = array();

        $comments = DB::table('users')
            ->join('comments', 'comments.author_id', '=', 'users.id')
            ->where('anime_id', $anime->id)
            ->orderBy('comments.id', 'desc')
            ->get();

        if (Auth::id()) {
            $likes = self::likes_helper($comments);
        }
        return View('animes.comments.show')->with('comments', $comments)->with('anime', $anime)->with('comment_like', $likes);
    }


    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => ['required', 'exists:App\Models\User,id'],
            'text' => ['required', 'string'],
            'anime_id'=> ['required', "exists:App\Models\Anime,id"]
        ]);

        /** @var string $text */
        $text = $request->text;

        Comment::create([
            'author_id' => $request->user_id,
            'text' =>  preg_replace('!\s+!', ' ', trim($text)),
            'anime_id' => $request->anime_id,
            ]);

        return back();
    }

    public function update(Request $request): Response
    {
        $request->validate([
            'id' => ['exists:comments', 'required'],
            'text' => ['required', "string"]
        ]);

        /** @var string $text */
        $text = $request->text;

        $text = preg_replace('!\s+!', ' ', trim($text));

        Comment::where('id', $request->id)->update([
            'text' => $text,
            'likes' => 0,
            'dislikes' => 0,
            ]);

        $like = DB::table('likes_comments')
            ->where('comment_id', $request->id)
            ->delete();
        return Response(strval($text));
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
        } else {
            if (isset($like->rate) and $like->rate != $request->status) {
                if ($request->status == "like") {
                    $comment->dislikes--;
                    $comment->likes++;
                } else {
                    $comment->dislikes++;
                    $comment->likes--;
                }

                DB::table('likes_comments')
                    ->where('user_id', $request->user_id)
                    ->where('comment_id', $request->id)
                    ->update(['rate' => $request->status]);
            }
        }
        $comment->save();
        return Response($comment->likes . ',' . $comment->dislikes);
    }
}
