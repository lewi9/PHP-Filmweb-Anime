<?php

namespace App\Helpers;

use App\Models\Anime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use stdClass;

trait ToHTML
{
    public function animeToHTML(Anime $anime): string
    {
        $output =
            '<div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div>
                <img class="center" src="' . URL::asset('/images/'.$anime->poster) . '" alt="Anime Pic" height="400" width="400"><br>' .
                $anime->title .
                '<br><a class="button" href="' . route('animes.show', [$anime->title, $anime->production_year, $anime->id]) . '">Details</a>
           </div></div><br>';
        return $output;
    }

    public function animeToRatingHTML(Anime $anime, int $number): string
    {
        $output =
            '<div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">' .
                    '<li><a href="' . route('animes.show', [$anime->title, $anime->production_year, $anime->id]) . '">' .
                     $number . ". " . $anime->title .
                    '</a>' .
                    '<img class="img" src="' . URL::asset('/images/'.$anime->poster) . '" alt="Anime Pic" height="400" width="400">' .
                '</li></div><br>';
        return $output;
    }

    public function commentToHTML(\stdClass $comment): string
    {
        $output =
            '<div id="' . $comment->id . 'div' . '">
                <label style="display:block" for="' . $comment->id . "_" . '">' .
                    $comment->name .
                '</label>
                <textarea id="' . $comment->id . "_" . '" name="text" rows="5" cols="60" disabled>' .
                    $comment->text . '
                .</textarea>
                <br>
                Likes: <mark id="' . $comment->id . 'likes' . '">' . $comment->likes . '</mark>
                Dislikes: <mark id="' . $comment->id . 'dislikes' . '">' . $comment->dislikes . '</mark>
                <button id="' . $comment->id . "__" . '" style="visibility: hidden" onclick="updater(this.id);">Update!</button>';
        if (Auth::user()) {
            $output .=
                '<br>
                <button style="background-color: lightgrey" id="' . $comment->id . '" name="liker-' . $comment->id . '" onclick="liker(this.id);">Like</button>
                <button style="background-color: lightgrey" id="' . $comment->id . '" name="disliker-' . $comment->id . '" onclick="disliker(this.id);">Dislike</button>
                <br>';

            if (Auth::id() == $comment->user_id) {
                $output .= '<button id = "' . $comment->id . '" onclick = "edit(this.id);" > Edit Comment </button >
                    <button id = "' . $comment->id . '" onclick = "deleter(this.id);" > Delete Comment </button >';
            }
        }
        $output .= '</div><br>';
        return $output;
    }

    public function reviewToHTML(\stdClass $review, Anime $anime): string
    {
        $output =
            '<div id="' . $review->id . 'div' . '">
                    <p><strong>' . $review->name . '</strong></p>
                    <p>' . $review->title . '</p>
                    <p>Review rating:' . $review->rating . '</p>
                    <a class="mini-button" href="' . route('reviews.show', [$anime->title, $anime->production_year, $anime->id, $review->id]) . '">Read review</a>';
        if (Auth::id() == $review->user_id) {
            $output .= '<a class="mini-button" href="' . route('reviews.edit', [$anime->title, $anime->production_year, $anime->id, $review->id]) . '">Edit review</a>
                        <form id="delete_review" action="' . route('reviews.delete', [$anime->title, $anime->production_year, $anime->id, $review->id]) .
                        '" method="post">' . csrf_field() . method_field('DELETE') .
                            '<a class="mini-button" href="javascript:{}" onclick="document.getElementById(\'delete_review\').submit(); return false;">Delete review</a>
                        </form>';
        }
        $output .= '<br> </div><br>';
        return $output;
    }

    public function animeCollectionToHTML(Collection $animes): string
    {
        $output = '<ol>';
        if (count($animes) > 0) {
            foreach ($animes as $anime) {
                /** @var Anime $anime */
                $output .= $this->animeToHTML($anime);
            }
            return $output . '</ol>';
        }
        return "<h2> There is no matching anime. </h2>";
    }

    public function animeCollectionToRatingHTML(Collection $animes): string
    {
        $output = '<ol>';
        if (count($animes) > 0) {
            $counter = 1;
            foreach ($animes as $anime) {
                /** @var Anime $anime */
                $output .= $this->animeToRatingHTML($anime, $counter);
                $counter += 1;
            }
            return $output . '</ol>';
        }
        return "<h2> There is no matching anime. </h2>";
    }

    public function reviewsCollectionToHTML(Collection $reviews, Anime $anime): string
    {
        $output = '';
        if (count($reviews) > 0) {
            foreach ($reviews as $review) {
                /** @var stdClass $review */
                $output .= $this->reviewToHTML($review, $anime);
            }
            return $output;
        }
        return "<h2> There are no Reviews. </h2>";
    }

    public function commentsCollectionToHTML(Collection $comments, Anime $anime): string
    {
        $output = '';
        if (count($comments) > 0) {
            foreach ($comments as $comment) {
                /** @var stdClass $comment */
                $output .= $this->commentToHTML($comment);
            }
            return $output;
        }
        return "<h2> There are no comments. </h2>";
    }
}
