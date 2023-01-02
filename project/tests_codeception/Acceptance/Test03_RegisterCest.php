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

        $I->wantTo("register proper user");

        $I->amOnPage('/register');

        $I->fillField('Name', $user->name);
        $I->fillField('Username', $user->username  );
        $I->fillField('Country', $user->country);
        $I->fillField('Email', $user->email);
        $I->fillField('Password', $user->password);
        $I->fillField('Confirm Password', $user->password);

        $I->click('Register');

        $I->seeCurrentUrlEquals('/dashboard');
        $I->see($user->name);
        $I->see("You're logged in!");

        ###
        $I->wantTo("register unproper user");
        $I->see("Log Out");

        $api_key = $I->grabValueFrom('input[name=_token]');
        $I->sendAjaxPostRequest('/logout',['_token' => $api_key]);
        $I->amOnPage('/register');
        $I->seeCurrentUrlEquals("/register");

        $I->fillField('Name', str_repeat($user->name,50));
        $I->fillField('Username', str_repeat($user->username  ,50));
        $I->fillField('Country', str_repeat($user->country,50));
        $I->fillField('Email', str_repeat($user->email,50));
        $I->fillField('Password', str_repeat($user->password,50));
        $I->fillField('Confirm Password', str_repeat($user->password,50));

        $I->click('Register');

        $I->seeCurrentUrlEquals('/register');



    }
}
