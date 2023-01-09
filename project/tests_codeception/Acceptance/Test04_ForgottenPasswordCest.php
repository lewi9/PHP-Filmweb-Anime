<?php

namespace TestsCodeception\Acceptance;

use TestsCodeception\Support\AcceptanceTester;

class Test04_ForgottenPasswordCest
{
    public function tryToTest(AcceptanceTester $I)
    {
        $I->wantTo("Recover Password");

        $I->amOnPage("/login");

        $I->click("Forgot your password?");
        $I->seeCurrentUrlEquals("/forgot-password");


        $I->fillField("Email", "");
        $I->click("Email Password Reset Link");
        //popupy ogarnac

        $I->fillField("Email", "dummy@mail.pl");
        $I->click("Email Password Reset Link");
        $I->see("We can't find a user with that email address.");

        $I->fillField("Email", "john.doe@gmail.com");
        $I->click("Email Password Reset Link");
        $I->see("We have emailed your password reset link!");

        $I->fillField("Email", "john.doe@gmail.com");
        $I->click("Email Password Reset Link");
        $I->see("Please wait before retrying.");
    }
}
