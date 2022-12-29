<?php

namespace App\Helpers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

trait likesHelper
{
    /**
     * @param Collection $comments
     * @return array<int, object|null>
     */

    public function likesHelper(Collection $comments): array
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
}
