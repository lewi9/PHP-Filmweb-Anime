<?php

namespace TestsCodeception\Acceptance;

use Codeception\Util\Locator;
use TestsCodeception\Support\AcceptanceTester;

class Test06_NewsPageTestCest
{
    public function tryToTest(AcceptanceTester $I)
    {
        $I->wantTo("Log in ");

        $I->amOnPage("/login");
        $I->fillField("Email", "john.doe@gmail.com");
        $I->fillField("Password", "secret");
        $I->click("Log in");
        $I->seeCurrentUrlEquals("/dashboard");
        $I->seeElement(['id' => 'logo_link']);
        $I->see("John Doe");


        $I->wantTo("Create new article ");
        $I->amOnPage("/dashboard");

        $I->seeElement(Locator::find('img', ['alt' => 'Anime Pic']));
        $I->seeElement(Locator::find("b", ['class' => 'News Title']));


        $dummyArticle = array(
            'title'=>"dummy title",
            "text"=>"dummy text",
            "likes"=>100,
            "dislikes"=>5

        );
        $I->haveInDatabase('articles', $dummyArticle);
        $I->seeInDatabase('articles', $dummyArticle);
        $dummyID = $I->grabFromDatabase('articles', "id", ['title'=>"dummy title"]);

        $I->amOnPage("/dashboard");
        $I->see("Likes: ". $dummyArticle["likes"]);

        $I->wantTo("Like post many times");

        $I->click(['name' => 'like_' . $dummyID]);
        $api_key = $I->getCSRF();
        $I->sendAjaxPostRequest('/articles/like/', ['_token' => $api_key, 'article_id' => $dummyID, 'user_id' => 1]);
        $I->sendAjaxPostRequest('/articles/like/', ['_token' => $api_key, 'article_id' => $dummyID, 'user_id' => 1]);

        $I->amOnPage("/dashboard");

        $I->see("Likes: ". ($dummyArticle["likes"]+1));
        $I->see("Dislikes: ". $dummyArticle["dislikes"]);

        $I->wantTo("Dislike");
        $I->sendAjaxPostRequest('/articles/dislike/', ['_token' => $api_key, 'article_id' => $dummyID, 'user_id' => 1]);
        $I->amOnPage("/dashboard");

        $I->see("Dislikes: ". ($dummyArticle["dislikes"]+1));
        $I->see("Likes: ". $dummyArticle["likes"]);
    }
}
