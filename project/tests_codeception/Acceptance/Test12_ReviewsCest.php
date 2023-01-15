<?php


namespace TestsCodeception\Acceptance;

use Codeception\Util\Locator;
use TestsCodeception\Support\AcceptanceTester;
use function PHPUnit\Framework\assertEquals;


class Test12_ReviewsCest
{
    public function tryToTest(AcceptanceTester $I)
    {
        $I->amOnPage("/login");
        $I->fillField("Email", "john.doe@gmail.com");
        $I->fillField("Password", "secret");
        $I->click("Log in");

        $I->amOnPage("/anime/Sailor%20moon-1992-1");
        $I->seeElement(['id'=>'reviews']);

        $I->click("Read review");
        $I->seeCurrentUrlEquals("/anime/Sailor%20moon-1992-1/reviews/1");

        $I->click("Edit review");
        $I->seeCurrentUrlEquals("/anime/Sailor%20moon-1992-1/reviews/1/edit");
        $I->fillField("Title","Great review");
        $I->fillField("Text","short review");
        $I->click("Update");

        $I->see("The text must be at least 500 characters.");

        $I->fillField("Text",str_repeat("long review",300));
        $I->click("Update");
        $I->seeCurrentUrlEquals("/anime/Sailor%20moon-1992-1/reviews/1");
        $I->see(str_repeat("long review",300));


        $I->click("All reviews");
        $I->click("Create review");
        $I->fillField("Title","Good review");
        $I->fillField("Text",str_repeat("Great review",300));
        $I->dontSeeInDatabase('reviews',['title'=>"Good review"]);
        $I->click("Create");
        $I->seeInDatabase('reviews',['title'=>"Good review"]);


        $I->wantTo("Sort reviews desc");

        $I->sendGet('/reviewsfilter', ['filter'=>'rating', 'anime_id' => 1,]);
        $I->sendGet('/reviewsfilter', ['filter_mode'=>'desc', 'anime_id' => 1]);
        $I->amOnPage('/anime/Sailor%20moon-1992-1/reviews');
        $temp = $I->grabMultiple(Locator::find("p", ["id"=>"rr_score"]));
        $reviews=[];

        foreach ($temp as $item){
        $item= explode(":",$item);
        $item = (int)$item[1];
        $reviews[] = $item;
        }
        $backup_reviews =$reviews ;
        sort($backup_reviews);
        assertEquals(array_reverse($backup_reviews), $reviews);
    }
}
