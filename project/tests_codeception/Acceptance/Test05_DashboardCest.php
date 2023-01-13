<?php

namespace TestsCodeception\Acceptance;

use Codeception\Coverage\Subscriber\Local;
use Codeception\Util\Locator;
use TestsCodeception\Support\AcceptanceTester;

class Test05_DashboardCest
{
    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        $I->amOnPage("/login");
        $I->fillField("Email", "john.doe@gmail.com");
        $I->fillField("Password", "secret");
        $I->click("Log in");
        $I->seeCurrentUrlEquals("/dashboard");
        $I->seeElement(['id' => 'logo_link']);
        $I->see("John Doe");



        $I->wantTo("Routing for Ratings page ");

        $I->click("Ratings");

//        $I->seeCurrentUrlEquals("/ratings");
        $I->seeElement(['id' => 'logo_link']);
        $I->seeElement(Locator::combine("ol", "li"));
        $I->see("John Doe");



        $I->wantTo("Route logo");
        $I->click(["id" => "logo_button"]);
        $I->seeCurrentUrlEquals("/dashboard");



        $I->wantTo("Routing for Search for animes page ");

        $I->click("Search for animes");

        $I->seeCurrentUrlEquals("/anime");
        $I->seeElement(['id' => 'logo_link']);
        $I->seeElement(['id' => "filter_genre"]);
        $I->see("John Doe");




        $I->amOnPage("/dashboard");

        $I->wantTo("Routing  News page");

        $I->click("News");

        $I->seeElement(['id' => 'logo_link']);
        $I->see("John Doe");
        $I->see("Like");
        $I->see("Dislike");



        $I->amOnPage("/dashboard");


        //NAPRRAWIC ZAPYTAC NA KONSULTACJACH

        /*
        $I->wantTo("Routing My profile");
//        $I->dontSeeInSource("My profile");
//        $I->dontSeeElement(["class" => "absolute z-50 mt-2 w-48 rounded-md shadow-lg origin-top-right right-0"]);

//        $I->click(["name"=>"user-button"]);
//
//        $I->seeElement(["class" => "absolute z-50 mt-2 w-48 rounded-md shadow-lg origin-top-right right-0"]);


        $I->click('My profile');
        $I->seeCurrentUrlEquals("/user/johndoe1");
        $I->see("My profile");
        $I->seeCurrentUrlEquals("/ratings/all/all");
*/

        $I->wantTo("Log Out");
//        $I->click("Log Out");
//        $api_key = $I->grabValueFrom('input[name=_token]');
//        $I->sendAjaxPostRequest('/logout', ['_token' => $api_key]);
//        $I->see("penis");
    }
}
