<?php

namespace App\Http\Controllers;

use App\Helpers\FilterHelper;
use App\Helpers\GetOrFail;
use App\Helpers\LikesHelper;
use App\Helpers\ToHTML;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CommentController extends Controller
{
    use GetOrFail;
    use ToHTML;
    use FilterHelper;
    use LikesHelper;



    public function show(string $title, int $production_year, int $id): View
    {
        $likes = array();

        $anime = $this->getOrFailAnime($id);

        $comments = DB::table('users')
            ->join('comments', 'comments.user_id', '=', 'users.id')
            ->where('anime_id', $anime->id)
            ->get();

        if (Auth::id()) {
            $likes = $this->likesHelper($comments);
        }

        return View('animes.comments.show')->with('comments', $this->filter(new Request(['anime_id' => $anime->id])))->with('anime', $anime)->with('comment_like', $likes);
    }


    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => ['required', 'exists:App\Models\User,id', 'regex:/^[_a-z0-9 ]+$/i'],
            'text' => ['required', 'string'],
            'anime_id'=> ['required', "exists:App\Models\Anime,id", 'regex:/^[_a-z0-9 ]+$/i']
        ]);

        /** @var string $text */
        $text = $request->text;

        Comment::create([
            'user_id' => $request->user_id,
            'text' =>  preg_replace('!\s+!', ' ', trim($text)),
            'anime_id' => $request->anime_id,
            ]);

        return back();
    }

    public function update(Request $request): Response
    {
        $request->validate([
            'id' => ['exists:comments', 'required', 'regex:/^[_a-z0-9 ]+$/i'],
            'text' => ['required', "string", 'regex:/^[_a-z0-9 ]+$/i']
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
        $request->validate([
           'id' => ['exists:comments,id', 'required', 'regex:/^[_a-z0-9 ]+$/i']
        ]);
        $comments = Comment::where('id', $request->id)->get();
        foreach ($comments as $comment) {
            $comment->forceDelete();
        }
    }

    public function like(Request $request): Response
    {
        $request->validate([
            'id' => ['required', 'exists:App\Models\Comment,id', 'regex:/^[_a-z0-9 ]+$/i'],
            'user_id'=> ['required', "exists:App\Models\User,id", 'regex:/^[_a-z0-9 ]+$/i'],
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

    public function filter(Request $request): Response
    {
        $request->validate([
            'anime_id' => ['exists:animes,id', 'required',  'regex:/^[_a-z0-9 ]+$/i']
        ]);

        $type = 'comments';

        return $this->filterProcedure($request, $type);
    }
}
