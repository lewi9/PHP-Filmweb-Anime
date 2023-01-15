<?php

namespace TestsCodeception\Acceptance;

use Codeception\Util\Locator;
use TestsCodeception\Support\AcceptanceTester;

use function PHPUnit\Framework\assertEquals;

class Test01_CommentsCest
{
    public function commentsTest(AcceptanceTester $I): void
    {
        /*
        $I->wantTo('see comments from DB displayed on page');

        $I->seeNumRecords(0, "comments");

        $randomNumber = rand();

        $title = "Title $randomNumber";
        $text = "Some text $randomNumber with **bold** text";

        $id = $I->haveInDatabase('comments', ['title' => $title, 'text' => $text]);

        $I->amOnPage('/comments');
        $I->see('Comments', 'h1');
        $I->seeLink($title, "/comments/$id");

        $I->click($title);
        $I->seeCurrentUrlEquals("/comments/$id");

        $I->see($title, 'h1');
        $textOnPage = str_replace("**bold**", "bold", $text);
        $I->see($textOnPage, 'p');
        $I->see("bold", 'p > strong');*/


        $I->amOnPage("/anime/Sailor%20moon-1992-1");
        $I->dontSee("Add comment");
        $I->dontSee("Like", "button");
//        $I->seeElement(['id' => 'logo_link']);

        $I->amOnPage("/login");
        $I->seeCurrentUrlEquals("/login");
        $I->fillField("Email", "john.doe@gmail.com");
        $I->fillField("Password", "secret");
        $I->click("Log in");

        $I->amOnPage("/anime/Sailor moon-1992-1");
        $I->see("Add comment");
        $I->see("Like", "button");

        $I->dontSeeInDatabase("comments", ["text"=>str_repeat("its a random comment ", 100)]);
        $I->fillField(["id"=>"text"], str_repeat("its a random comment ", 100));
        $I->seeInField(["id"=>"text"], str_repeat("its a random comment ", 100));
        $I->click("Add comment");
        $I->seeInDatabase("comments", ["text"=>str_repeat("its a random comment ", 100),"likes"=>0,"dislikes"=>0]);

        $added_comment = $I->grabEntryFromDatabase("comments", ["text"=>str_repeat("its a random comment ", 100)]);
        $commentID = $added_comment["id"];

        $I->click(Locator::find("button", ['id'=>"$commentID"]));
        // comment edit
        $I->sendGet('/commentsupdate', ['id'=>$commentID, 'text'=>str_repeat("nice ecchi ", 100)]);
        $I->seeInDatabase("comments", ["text"=>str_repeat("nice ecchi ", 100)]);

        $I->amOnPage("/anime/Sailor moon-1992-1");


        $like = $I->grabTextFrom(["id" => $commentID . "likes"]);
        $I->see($like, "mark");

        $I->sendGet('/commentslike', ['id'=> $commentID,"user_id"=>"1", 'status'=>'like']);
        $I->amOnPage("/anime/Sailor moon-1992-1");
        $I->see(($like+1), Locator::find("mark", ["id" => $commentID . "likes"]));

        $I->sendGet('/commentslike', ['id'=> $commentID,"user_id"=>"1", 'status'=>'dislike']);
        $I->amOnPage("/anime/Sailor moon-1992-1");

        $I->see($like, Locator::find("mark", ["id" => $commentID . "likes"]));
        $I->see($like+1, Locator::find("mark", ["id" => $commentID . "dislikes"]));


        $I->click("Delete Comment");
        $I->sendGet("/commentsdelete", ["id"=>$commentID]);
        $I->amOnPage("/anime/Sailor moon-1992-1");
        $I->dontSeeInDatabase("comments", ["text"=>str_repeat("nice ecchi ", 100)]);

        $I->wantTo("Sort dislikes ascending ");

        $I->amOnPage('/anime/Sailor moon-1992-1/comments');
        $I->sendGet('/commentsfilter', ['filter'=>'dislikes', 'anime_id' => 1,]);
        $I->sendGet('/commentsfilter', ['filter_mode'=>'asc', 'anime_id' => 1]);
        $I->amOnPage('/anime/Sailor moon-1992-1/comments');
        $dislikes = $I->grabMultiple(Locator::find("mark", ["class"=>"D1"]));
        $backup_dislikes =$dislikes ;
        sort($backup_dislikes);
        assertEquals($backup_dislikes, $dislikes);

        $I->wantTo("Sort likes desc");

        $I->sendGet('/commentsfilter', ['filter'=>'likes', 'anime_id' => 1,]);
        $I->sendGet('/commentsfilter', ['filter_mode'=>'desc', 'anime_id' => 1]);
        $I->amOnPage('/anime/Sailor moon-1992-1/comments');
        $likes = $I->grabMultiple(Locator::find("mark", ["class"=>"L1"]));
        $backup_likes =$likes ;
        sort($backup_likes);
        assertEquals(array_reverse($backup_likes), $likes);
    }
}
