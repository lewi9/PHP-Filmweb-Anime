<?php

namespace TestsCodeception\Acceptance;

use Codeception\Util\Locator;
use TestsCodeception\Support\AcceptanceTester;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotEquals;

class Test08_SearchForAnimesCest
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


        $I->amOnPage("/anime");

        $I->seeElement(Locator::find('select', ['id' => 'filter']));
        $I->seeElement(Locator::find('select', ['id' => 'filter_mode']));
        $I->seeElement(Locator::find('select', ['id' => 'filter_genre']));
        $I->seeElement(Locator::combine("ol", "li"));
        $I->see("Clear filters");


        $I->wantTo("Get all animes details to see if they are sorted correct");


        $sorting = array(
            'rating' => "anime_rating",
            'production_year' => 'anime_year',
            'title' => 'anime_title',
            "how_much_users_watched" => 'anime_hmuc'

        );

        foreach ($sorting as $key => $value) {
            $I->wantTo("Check corectness of sorting for " . $key);


            //$I->selectOption("select[name=filter]", $key);
            $I->sendGet('/anime/filter/', [ 'filter' => $key]);
            //$I->selectOption("select[name=filter_mode]", "descending");
            $I->sendGet('/anime/filter', ['filter_mode' => 'desc']);
            $I->amOnPage("/anime");
            $key = str_replace('_', ' ', $key);
            $key = $key == "how much users watched" ? "watches" : $key;
            $I->seeOptionIsSelected("form select[name=filter]", $key);
            $I->seeOptionIsSelected("form select[name=filter_mode]", "descending");

            $animeLinks = $I->grabMultiple("//a");

            $watches = [];
            foreach ($animeLinks as $link) {
                if ($link == 'Details') {
                    $I->click($link);
                    $watches[] = $I->grabTextFrom(Locator::find("p", ['id' => $value]));
                    $I->moveBack(1);
                }
            }
            $backup_watches = [];
            foreach ($watches as $element) {
                $backup_watches[] = $element;
            }

            sort($backup_watches);
            assertEquals($watches, $backup_watches);
            $backup_watches[] = 1;
            assertNotEquals($watches, $backup_watches);
            $I->amOnPage("/anime");
        }


        $I->seeCurrentUrlEquals("/anime");
        $genres = ['adventure', 'magical girl', 'apocalyptic', 'mecha'];

        foreach ($genres as $genre) {
            $I->sendGet('/anime/filter/', [ 'filter_genre' => $genre]);
            $I->amOnPage("/anime");

//            $I->selectOption("//select[@id = 'filter_genre']", $genre);
            $I->seeOptionIsSelected("//select[@id = 'filter_genre']", $genre);
            $numberOfElements = count($I->grabMultiple(Locator::find('img', ['alt' => 'Anime Pic'])));
            $I->seeNumRecords($numberOfElements, 'animes', ['genre like' => "%$genre%"]);
        }


        $I->wantTo("Type text to search anime by title");

        $I->sendGet('anime/filter', ['filter_genre' => 'all']);
        $I->sendGet('/anime/filter', ['filter_search' => 'One Pie']);

        $I->see("One Piece",);
        $I->dontSee('Neon Genesis Evangelion');
        $I->dontSee('Sailor moon');

    }
}
