<?php

namespace TestsCodeception\Acceptance;

use TestsCodeception\Support\AcceptanceTester;

class Test02_LoginCest
{
    public function loginTest(AcceptanceTester $I): void
    {
        $I->wantTo('login with existing user');

        $I->amOnPage('/dashboard');

        $I->seeCurrentUrlEquals('/login');

        $I->fillField('email', 'john.doe@gmail.com');
        $I->fillField('password', 'secret');

        $I->click('Log in');

        $I->seeCurrentUrlEquals('/dashboard');

        $I->see('John Doe');
        $I->see("You're logged in!");
    }

    public function wrongLogin(AcceptanceTester $I) :void
    {
        $I->wantTo('login with non-existing login');

        $I->dontSeeInDatabase("users",['email' => "dummy@gmail.com"]);

        $I->amOnPage('/dashboard');

        $I->seeCurrentUrlEquals('/login');

        $I->fillField('email', 'dummy@gmail.com');
        $I->fillField('password', 'dummy');

        $I->click('Log in');
        $I->seeCurrentUrlEquals('/login');
        $I->see('These credentials do not match our records.');

    }

    public function wrongPassword(AcceptanceTester $I) :void
    {
        $I->wantTo('Login with wrong password');

        $I->seeInDatabase("users",['email' => "john.doe@gmail.com"]);

        $I->amOnPage('/dashboard');

        $I->seeCurrentUrlEquals('/login');

        $I->fillField('email', 'john.doe@gmail.com');
        $I->fillField('password', 'dummy123');

        $I->click('Log in');
        $I->seeCurrentUrlEquals('/login');
        $I->see('These credentials do not match our records.');

    }
    public function emptyLogin(AcceptanceTester $I) :void
    {
        $I->wantTo('Login without Login');
        $I->amOnPage('/dashboard');
        $I->seeCurrentUrlEquals('/login');

        $I->fillField('email', "");
        $I->fillField('password', "");

        $I->click('Log in');
        $I->seeCurrentUrlEquals('/login');
        $I->see("The email field is required.");

    }

    public function emptyPassword(AcceptanceTester $I) :void
    {
        $I->wantTo('Login without password');

        $I->seeInDatabase("users",['email' => "john.doe@gmail.com"]);

        $I->amOnPage('/dashboard');
        $I->seeCurrentUrlEquals('/login');

        $I->fillField('email', 'john.doe@gmail.com');
        $I->fillField('password', "");

        $I->click('Log in');
        $I->seeCurrentUrlEquals('/login');
        $I->see("The password field is required.");
    }
}
