<?php

namespace TestsCodeception\Acceptance;

use Codeception\Util\Locator;
use TestsCodeception\Support\AcceptanceTester;

class Test09_ProfileFunctionalitiesCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage("/login");
        $I->seeCurrentUrlEquals("/login");
        $I->fillField("Email", "john.doe@gmail.com");
        $I->fillField("Password", "secret");
        $I->click("Log in");
        $I->seeCurrentUrlEquals("/dashboard");
        $I->seeElement(['id' => 'logo_link']);
        $I->see("John Doe");
    }
    public function RemoveAddFriend(AcceptanceTester $I)
    {
        $I->wantTo("Remove and invite friend");


        $I->amOnPage("user/janedoe");

        $I->click('Delete from friends');
        $I->see("You've deleted a friend!");

        $I->click('Add to friends');
        $I->see("Invitation is pending");

        $I->click("Friends");

        $I->dontSee("John Doe", "a");

        $I->wantTo(" Swipe user to accept invitation");

        $api_key = $I->getCSRF();
        $I->sendAjaxPostRequest('/logout', ['_token' => $api_key]);

        $I->amOnPage("/user/janedoe/invitations");
        $I->seeCurrentUrlEquals("/login");
        $I->fillField("Email", "jd@gmail.com");
        $I->fillField("Password", "secret");
        $I->click("Log in");

        $I->see("John Doe");
        $I->see("Accept");
        $I->see("Delete");

        $I->click("Accept");

        $I->dontSee("John Doe");

        $I->amOnPage("/user/janedoe/friends");
        $I->see("John Doe");
    }
    public function Animes(AcceptanceTester $I)
    {
        $I->wantTo("Change number of watched episodes");

        $I->amOnPage("/user/johndoe1/watched-episodes");

        $I->see("200/200");
        $I->click("Sailor moon");
        $I->seeCurrentUrlEquals("/anime/Sailor%20moon-1992-1");
        $I->sendGet('/anime/watched_episodes', [ "episodes"=>'100',"user_id" => "1","anime_id"=>"1"]);
        $I->amOnPage("/user/johndoe1/watched-episodes");
        $I->see("100/200");


        $I->wantTo("Add ratings for anime");

        $I->amOnPage("/user/johndoe1/ratings");
        $I->sendGet('/anime/rate', [ "rating"=>'5',"user_id" => "1","anime_id"=>"2"]);
        $I->amOnPage("/user/johndoe1/ratings");

        $I->see("Sailor moon");
        $I->see("10/10");
        $I->see("Neon Genesis Evangelion ");
        $I->see("5/10");

        $I->wantTo("Add new favorite anime");

        $I->amOnPage("/anime/Sailor moon-1992-1");
        $I->see("Remove from favorite");
        $I->sendGet('/anime/manage_list', [ "list"=>'favorite',"user_id" => "1","anime_id"=>"1"]);

        $I->amOnPage("/anime/Sailor moon-1992-1");
        $I->see("Add to fav animes");

        $I->amOnPage("/user/johndoe1/favorites");
        $I->dontSee("Sailor moon");
        $I->sendGet('/anime/manage_list', [ "list"=>'favorite',"user_id" => "1","anime_id"=>"1"]);
        $I->amOnPage("/user/johndoe1/favorites");
        $I->see("Sailor moon");

        $I->wantTo("Remove and add anime to watch");
        $I->amOnPage("/user/johndoe1/to-watch");
        $I->see("Sailor moon");
        $I->sendGet('/anime/manage_list', [ "list"=>'to_watch',"user_id" => "1","anime_id"=>"1"]);
        $I->amOnPage("/user/johndoe1/to-watch");
        $I->dontSee("Sailor moon");
    }
}
