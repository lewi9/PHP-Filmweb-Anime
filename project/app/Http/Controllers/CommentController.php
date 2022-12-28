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

    public function show(string $title, int $production_year, int $id): View
    {
        $likes = array();

        $anime = AnimeController::anime_helper($id);

        $comments = DB::table('users')
            ->join('comments', 'comments.author_id', '=', 'users.id')
            ->where('anime_id', $anime->id)
            ->get();

        if (Auth::id()) {
            $likes = self::likes_helper($comments);
        }

        return View('animes.comments.show')->with('comments', $this->filter(new Request(['anime_id' => $anime->id])))->with('anime', $anime)->with('comment_like', $likes);
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

    public function filter(Request $request): Response
    {
        $request->validate([
            'anime_id' => ['exists:animes,id', 'required']
        ]);

        $anime = AnimeController::anime_helper(intval($request->anime_id));

        $output = "";
        $filter = $request->filter ?? (session('comments_filter')?? "id");
        $filter_mode = $request->filter_mode ?? (session('comments_filter_mode') ?? "asc");

        session(["comments_filter" => $filter, "comments_filter_mode" => $filter_mode]);

        $comments = DB::table('users')
            ->join('comments', 'comments.author_id', '=', 'users.id')
            ->where('anime_id', $anime->id)
            ->orderBy("comments.". strval($filter), strval($filter_mode))
            ->get();

        if (count($comments) > 0) {
            foreach ($comments as $comment) {
                /** @var stdClass $comment */
                $output .= '<div id="' . e($comment->id . 'div') . '">
                    <label style="display:block" for="' . e($comment->id . "_") . '">' . e($comment->name) . '</label>
                    <textarea style="display:block" id="' . e($comment->id . "_") . '" name="text" rows="4" cols="50" disabled>' . e($comment->text) . '.</textarea>
                    <br>
                    Likes: <mark id="' . e($comment->id . 'likes') . '">' . e($comment->likes) . '</mark>
                    Dislikes: <mark id="' . e($comment->id . 'dislikes') . '">' . e($comment->dislikes) . '</mark>
                    <button id="' . e($comment->id . "__") . '" style="visibility: hidden" onclick="updater(this.id);">Update!</button>';
                if (Auth::user()) {
                    $output .= '<br>
                        <button style="background-color: lightgrey" id="' . e($comment->id) . '" name="liker-' . e($comment->id) . '" onclick="liker(this.id);">Like</button>
                        <button style="background-color: lightgrey" id="' . e($comment->id) . '" name="disliker-' . e($comment->id) . '" onclick="disliker(this.id);">Dislike</button>
                        <br>';

                    if (Auth::id() == $comment->author_id) {
                        $output .= '<button id = "' . e($comment->id) . '" onclick = "edit(this.id);" > Edit Comment </button >
                            <button id = "' . e($comment->id) . '" onclick = "deleter(this.id);" > Delete Comment </button >';
                    }
                }
                $output .= '</div>';
            }
            return Response($output);
        }
        return Response("<h2> There is no matching comments. </h2>");
    }
}
