<?php

namespace TestsCodeception\Acceptance;

use Codeception\Util\Locator;
use TestsCodeception\Support\AcceptanceTester;

class Test10_ProfileConfigurationsCest
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

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        $I->wantTo("Set profile image");
        $I->amOnPage("/user/johndoe1/edit");
        $I->attachFile('input[name=image]', "missing.jpg");
        $I->click("Upload");
        $I->see("Image uploaded successfully!");

        $I->wantTo("Update profile information");

        $I->fillField("Name", str_repeat("Naruto Uzumaki", 40));
        $I->click("Save");
        $I->see("The name must not be greater than 255 characters.");


        $I->fillField("Name", "Naruto Uzumaki");
        $I->fillField("Email", "its@notmail");
        $I->click("Save");
        $I->see("The email must be a valid email address.");

        $I->seeInDatabase("users", ["email"=>"admin@anime.pl"]);
        $I->fillField("Email", "admin@anime.pl");
        $I->click("Save");
        $I->see("The email has already been taken.");

        $I->fillField("Name", "Naruto Uzumaki");
        $I->fillField("Email", "xd@xd.pl");
        $I->click("Save");
        $I->dontSee("John Doe");
        $I->seeInDatabase("users", ['name' => 'Naruto Uzumaki', "email"=>"xd@xd.pl"]);




        $I->wantTo("Update Password");

        $I->fillField("Current Password", "incorrect");
        $I->fillField("New Password", "NarutoUzumaki");
        $I->fillField("Confirm Password", "NarutoUzumaki");
        $I->click(["id"=>"password_save"]);
        $I->see("The password is incorrect.");


        $I->fillField("Current Password", "secret");
        $I->fillField("New Password", "");
        $I->fillField("Confirm Password", "");
        $I->click(["id"=>"password_save"]);
        $I->see("The password field is required.");


        $I->fillField("Current Password", "secret");
        $I->fillField("New Password", "a");
        $I->fillField("Confirm Password", "a");
        $I->click(["id"=>"password_save"]);
        $I->see("The password must be at least 8 characters.");

        $I->fillField("Current Password", "secret");
        $I->fillField("New Password", "12345678");
        $I->fillField("Confirm Password", "12345679");
        $I->click(["id"=>"password_save"]);
        $I->see("The password confirmation does not match.");

        $I->fillField("Current Password", "secret");
        $I->fillField("New Password", "12345678");
        $I->fillField("Confirm Password", "12345678");
        $I->click(["id"=>"password_save"]);

        $api_key = $I->getCSRF();
        $I->sendAjaxPostRequest('/logout', ['_token' => $api_key]);

        $I->amOnPage("/login");
        $I->fillField("Email", "xd@xd.pl");
        $I->fillField("Password", "12345678");
        $I->click("Log in");
        $I->see("You're logged in!");


//        $I->wantTo("Delete Account");
//        $I->amOnPage("/user/johndoe1/edit");
        ////        $I->dontSee("Are you sure your want to delete your account?");
//        $I->click("Delete Account");
//        $I->see("Are you sure your want to delete your account?");
//
//        $I->click("Cancel");
    }
}
