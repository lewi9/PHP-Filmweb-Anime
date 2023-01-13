<?php

namespace App\Http\Controllers;

use App\Helpers\GetOrFail;
use App\Helpers\HasEnsure;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ArticleController extends Controller
{
    use GetOrFail;
    use HasEnsure;
    public function like(Request $request): Response
    {
        return $this->like_or_dislike($request);
    }
    public function dislike(Request $request): Response
    {
        return $this->like_or_dislike($request, false);
    }
    public function like_or_dislike(Request $request, bool $is_like = true): Response
    {
        $request->validate([
            'article_id' => ['required', 'exists:App\Models\Article,id', 'regex:/^[_a-z0-9 ]+$/i']
        ]);
        /** @var string $article_id */
        $article_id = $request->article_id;
        $article = $this->getOrFailArticle($article_id);
        $user = $this->ensureIsNotNullUser($request->user());
        $user_article = DB::table('likes_articles')
            ->where('user_id', $user->id)
            ->where('article_id', $article_id)
            ->first();
        if (!$user_article) {
            if ($is_like) {
                DB::table('likes_articles')
                    ->insert([
                        'user_id' => $user->id,
                        'article_id' => $article_id,
                        'is_like' => true,
                    ]);
                $article->likes += 1;
            } else {
                DB::table('likes_articles')
                    ->insert([
                        'user_id' => $user->id,
                        'article_id' => $article_id,
                        'is_like' => false,
                    ]);
                $article->dislikes += 1;
            }
            $article->save();
            return Response($article->likes . ',' . $article->dislikes);
        } elseif ($user_article->is_like) {  /** @phpstan-ignore-line */
            if ($is_like) {
                return Response($article->likes . ',' . $article->dislikes);
            } else {
                $article->likes -= 1;
                $article->dislikes += 1;
                $article->save();
                $this->update_database_is_like($user->id, $article_id, false);
                return Response($article->likes . ',' . $article->dislikes);
            }
        } else {
            if (!$is_like) {
                return Response($article->likes . ',' . $article->dislikes);
            } else {
                $article->likes += 1;
                $article->dislikes -= 1;
                $article->save();
                $this->update_database_is_like($user->id, $article_id, true);
                return Response($article->likes . ',' . $article->dislikes);
            }
        }
    }
    private function update_database_is_like(mixed $user_id, mixed $article_id, bool $value): void
    {
        DB::table('likes_articles')
            ->where('user_id', $user_id)
            ->where('article_id', $article_id)
            ->update(['is_like' => $value]);
    }
}
