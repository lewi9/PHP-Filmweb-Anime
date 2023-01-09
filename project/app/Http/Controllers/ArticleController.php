<?php

namespace App\Http\Controllers;

use App\Helpers\GetOrFail;
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
            'article_id' => ['required', 'exists:App\Models\Article,id']
        ]);
        $article_id = $request->article_id;
        $article = $this->getOrFailArticle($article_id);
        if ($is_like) {
            $article->likes += 1;
            $article->save();
            return Response($article->likes);
        } else {
            $article->dislikes += 1;
            $article->save();
            return Response($article->dislikes);
        }
    }
}
