<?php
//
//namespace TestsCodeception\Acceptance;
//
//use Codeception\Util\Locator;
//use TestsCodeception\Support\AcceptanceTester;
//
//class Test07_RatingsCest
//{
//    public function ratingTest(AcceptanceTester $I)
//    {
//        $I->wantTo('test ratings');
//        $I->amOnPage("/login");
//        $I->fillField("Email", "john.doe@gmail.com");
//        $I->fillField("Password", "secret");
//        $I->click("Log in");
//        $I->seeCurrentUrlEquals("/dashboard");
//        $I->amOnPage('/ratings');
////        $I->see('dupa');
////        $I->seeElement(['id' => 'logo_link']);
////        $I->see('Choose production year: ', Locator::find('label', ['for' => 'production_year']));
//        $I->see('all', Locator::find('option', ['value' => 'all']));
//        $I->seeElement(Locator::find('select', ['name' => 'production_year']));
//        $I->seeElement(Locator::find('select', ['name' => 'genre']));
//
//        $option1 = $I->grabTextFrom('select[name=production_year] option:nth-child(2)');
//        $option2 = $I->grabTextFrom('select[name=genre] option:nth-child(3)');
//
//        $I->selectOption("select[name=production_year]", $option1);
//        $I->selectOption("select[name=genre]", $option2);
//
//        $I->seeOptionIsSelected('form select[name=production_year]', $option1);
//        $I->seeOptionIsSelected('form select[name=genre]', $option2);
//
//        $I->click("Filter");
//        $I->seeCurrentUrlEquals("/ratings/calculate");
//        $I->seeElement('a');
//        $I->click();
////        $I->amOnPage("/ratings");
////        $I->see('all', Locator::find('option', ['value' => 'all']));
//
////        $I->see();
//    }
//}
