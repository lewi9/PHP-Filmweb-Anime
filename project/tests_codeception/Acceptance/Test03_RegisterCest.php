<?php

namespace TestsCodeception\Acceptance;

use App\Models\User;
use TestsCodeception\Support\AcceptanceTester;

class Test03_RegisterCest
{
    public function register(AcceptanceTester $I): void
    {
        $user = new User();
        $user->name ='Mokey D. Luffy';
        $user->username = 'Luffy';
        $user->country = 'Poland';
        $user->email = 'capitan@gmail.com';
        $user->password = "OnePiece123";

        $I->wantTo("Invalid Name and Username (to many characters)");
        $I->amOnPage('/');
        $I->click("Register");

        $I->fillField('Name', str_repeat($user->name, 50));
        $I->fillField('Username', str_repeat($user->username, 55));
        $I->fillField('Country', $user->country);
        $I->fillField('Email', $user->email);
        $I->fillField('Password', $user->password);
        $I->fillField('Confirm Password', $user->password);

        $I->click('Register');
        $I->see("The name must not be greater than 255 characters.");
        $I->see("The username must not be greater than 255 characters.");


        // powinny juz przechodzic
        $I->wantTo("Fill invalid country");

        $I->fillField('Name', $user->name);
        $I->fillField('Username', $user->username);
        $I->fillField('Country', str_repeat($user->country, 70));
        $I->fillField('Email', $user->email);
        $I->fillField('Password', $user->password);
        $I->fillField('Confirm Password', $user->password);

        $I->click('Register');
        $I->see("The country must not be greater than 255 characters.");

        $I->wantTo("Fill unproper mail.");

        $I->fillField('Name', $user->name);
        $I->fillField('Username', $user->username);
        $I->fillField('Country', $user->country);
        $I->fillField('Email', "itsnot@mail");
        $I->fillField('Password', $user->password);
        $I->fillField('Confirm Password', $user->password);

        $I->click('Register');
        $I->see("The email must be a valid email address.");


        $I->wantTo("Fill too short password");

        $I->fillField('Name', $user->name);
        $I->fillField('Username', $user->username);
        $I->fillField('Country', $user->country);
        $I->fillField('Email', $user->email);
        $I->fillField('Password', "ok");
        $I->fillField('Confirm Password', "ok");

        $I->seeCurrentUrlEquals('/register');
        $I->click("Register");

        $I->see("The password must be at least 8 characters.");


        // do ogarnieca PopUpy
        /*
        $I->wantTo("Empty name");

        $I->fillField('Name', "");
        $I->fillField('Username', "");
        $I->fillField('Country', "");
        $I->fillField('Email', "");
        $I->fillField('Password', "");
        $I->fillField('Confirm Password', "");

        $I->seeCurrentUrlEquals('/register');
        $I->click("Register");
        //////////
        $I->wantTo("Empty username");

        $I->fillField('Name', $user->name);
        $I->fillField('Username', "");
        $I->fillField('Country', "");
        $I->fillField('Email', "");
        $I->fillField('Password', "");
        $I->fillField('Confirm Password', "");

        $I->seeCurrentUrlEquals('/register');
        $I->click("Register");

        $I->wantTo("Empty country");

        $I->fillField('Name', $user->name);
        $I->fillField('Username', $user->name);
        $I->fillField('Country', "");
        $I->fillField('Email', "");
        $I->fillField('Password', "");
        $I->fillField('Confirm Password', "");

        $I->seeCurrentUrlEquals('/register');
        $I->click("Register");

        $I->wantTo("Empty email field");

        $I->fillField('Name', $user->name);
        $I->fillField('Username', $user->name);
        $I->fillField('Country', $user->country);
        $I->fillField('Email', "");
        $I->fillField('Password', "");
        $I->fillField('Confirm Password', "");

        $I->seeCurrentUrlEquals('/register');
        $I->click("Register");

        $I->wantTo("Empty password field");

        $I->fillField('Name', $user->name);
        $I->fillField('Username', $user->name);
        $I->fillField('Country', $user->country);
        $I->fillField('Email', $user->country);
        $I->fillField('Password', "");
        $I->fillField('Confirm Password', "");

        $I->seeCurrentUrlEquals('/register');
        $I->click("Register");

        $I->wantTo("Empty confirm password field");

        $I->fillField('Name', $user->name);
        $I->fillField('Username', $user->name);
        $I->fillField('Country', $user->country);
        $I->fillField('Email', $user->country);
        $I->fillField('Password', $user->password);
        $I->fillField('Confirm Password', "");

        $I->seeCurrentUrlEquals('/register');
        $I->click("Register");

*/
        $I->wantTo("Password confirm does not match");

        $I->fillField('Name', $user->name);
        $I->fillField('Username', $user->name);
        $I->fillField('Country', $user->country);
        $I->fillField('Email', $user->country);
        $I->fillField('Password', $user->password);
        $I->fillField('Confirm Password', "unproper");

        $I->seeCurrentUrlEquals('/register');
        $I->click("Register");

        $I->see("The password confirmation does not match");


        $I->wantTo("register proper user");

        $I->fillField('Name', $user->name);
        $I->fillField('Username', $user->username);
        $I->fillField('Country', $user->country);
        $I->fillField('Email', $user->email);
        $I->fillField('Password', $user->password);
        $I->fillField('Confirm Password', $user->password);

        $I->seeCurrentUrlEquals('/register');
        $I->click('Register');
        $I->seeCurrentUrlEquals('/dashboard');
    }
}
