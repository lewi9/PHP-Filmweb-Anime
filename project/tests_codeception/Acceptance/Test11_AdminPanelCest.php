<?php

namespace TestsCodeception\Acceptance;

use TestsCodeception\Support\AcceptanceTester;

class Test11_AdminPanelCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->wantTo('Register and login new admin');

        $I->amOnPage('/admin/register');

        $I->fillField('name', 'New Admin');
        $I->fillField('username', 'newadmin');
        $I->fillField('email', 'newadmin@anime.pl');
        $I->fillField('password', 'password');
        $I->fillField('password_confirmation', 'password');
        $I->click('Register');
        $I->seeCurrentUrlEquals('/admin/login');
        $I->fillField('email', 'newadmin@anime.pl');
        $I->fillField('password', 'password');

        $I->click('Login');
        $I->seeCurrentUrlEquals('/admin/dashboard');
    }
    public function addAnimes(AcceptanceTester $I)
    {
        $I->wantTo('Add new Anime');

        $I->click('Animes');
        $I->seeCurrentUrlEquals('/admin/anime');
        $I->click('Add anime');
        $I->seeCurrentUrlEquals('/admin/anime/create');
        $I->dontSeeInDatabase('animes', ['title' => 'Perfect Blue']);

        $I->fillField('title', 'Perfect Blue');
        $I->fillField('genre', 'thriller');
        $I->fillField('production_year', 1997);
        $I->fillField('poster', -1);
        $I->fillField('description', 'Perfect Blue is a 1997 Japanese animated psychological thriller film directed by Satoshi Kon.');
        $I->fillField('rating', 0.0);
        $I->fillField('how_much_users_watched', 0);
        $I->fillField('rates', 0);
        $I->fillField('cumulate_rating', 0);
        $I->fillField('episodes', 1);

        $I->click('Save and back');
        $I->seeInDatabase('animes', ['title' => 'Perfect Blue']);
    }
    public function addAnimesUsers(AcceptanceTester $I)
    {
        $I->wantTo('Add new Anime Users Relation');

        $I->click('Anime users');
        $I->seeCurrentUrlEquals('/admin/anime-users');
        $I->click('Add anime users');
        $I->seeCurrentUrlEquals('/admin/anime-users/create');
        $I->dontSeeInDatabase('anime_users', ['user_id' => '2', 'anime_id' => '1']);

        $I->fillField('user_id', '2');
        $I->fillField('anime_id', '1');
        $I->fillField('would_like_to_watch', 1);
        $I->fillField('favorite', 0);
        $I->fillField('rating', '0');
        $I->fillField('watched', 0);
        $I->fillField('watched_episodes', 0);
        $I->click('Save and back');
        $I->seeInDatabase('anime_users', ['user_id' => '2', 'anime_id' => '1']);
    }
    public function addComments(AcceptanceTester $I)
    {
        $I->wantTo('Add new comment');

        $I->click('Comments');
        $I->seeCurrentUrlEquals('/admin/comment');

        $I->click('Add comment');
        $I->seeCurrentUrlEquals('/admin/comment/create');
        $I->dontSeeInDatabase('comments', ['text' => 'test comment']);

        $I->fillField('text', 'test comment');
        $I->fillField('user_id', '1');
        $I->fillField('anime_id', '1');
        $I->fillField('likes', 1);
        $I->fillField('dislikes', 0);

        $I->click('Save and back');
        $I->seeInDatabase('comments', ['text' => 'test comment']);
    }
    public function addReviews(AcceptanceTester $I)
    {
        $I->wantTo('Add new review');

        $I->click('Reviews');
        $I->seeCurrentUrlEquals('/admin/review');
        $I->click('Add review');

        $I->seeCurrentUrlEquals('/admin/review/create');
        $I->dontSeeInDatabase('reviews', ['title' => 'test review']);

        $I->fillField('title', 'test review');
        $I->fillField('text', 'test text');
        $I->fillField('user_id', '1');
        $I->fillField('anime_id', '1');
        $I->fillField('cumulate_rating', 10);
        $I->fillField('rates', 2);
        $I->click('Save and back');
        $I->seeInDatabase('reviews', ['title' => 'test review']);
    }
    public function addReviewUsers(AcceptanceTester $I)
    {
        $I->wantTo('Add new review Users Relation');

        $I->click('Review users');
        $I->seeCurrentUrlEquals('/admin/review-users');
        $I->click('Add review users');
        $I->seeCurrentUrlEquals('/admin/review-users/create');
        $I->dontSeeInDatabase('review_users', ['user_id' => '1', 'review_id' => '1']);

        $I->fillField('user_id', '1');
        $I->fillField('review_id', '1');
        $I->fillField('rating', '10');
        $I->click('Save and back');
        $I->seeInDatabase('review_users', ['user_id' => '1', 'review_id' => '1']);
    }
    public function addArticles(AcceptanceTester $I)
    {
        $I->wantTo('Add new Article');
        $I->click('Articles');
        $I->seeCurrentUrlEquals('/admin/article');
        $I->click('Add article');
        $I->seeCurrentUrlEquals('/admin/article/create');
        $I->dontSeeInDatabase('articles', ['title' => 'test news']);

        $I->fillField('title', 'test news');
        $I->fillField('text', 'test text');
        $I->fillField('likes', 1);
        $I->fillField('dislikes', 0);
        $I->click('Save and back');
        $I->seeInDatabase('articles', ['title' => 'test news']);
    }
    public function checkAuthAdminFunctionality(AcceptanceTester $I)
    {
        $I->wantTo('Add new Anime another way');
        $I->amOnPage('/login');
        $I->fillField('email', 'admin@anime.pl');
        $I->fillField('password', 'password');
        $I->click('Log in');
        $I->seeCurrentUrlEquals('/dashboard');
        $I->amOnPage('/anime');
        $I->see('Create new...');
        $I->click('Create new...');

        $I->seeCurrentUrlEquals('/anime/create');
        $I->fillField('title', 'Naruto');
        $I->fillField('genre', 'Adventure');
        $I->fillField('production_year', 2002);
        $I->fillField('description', 'Naruto Uzumaki, a mischievous adolescent ninja, struggles as he searches for recognition and dreams of becoming the Hokage.');
        $I->fillField('episodes', 100);
        $I->click('Create');

        $I->seeCurrentUrlMatches('%Naruto-2002%');
        $I->seeInDatabase('animes', ['title' => 'Naruto']);
        $I->click('Edit');

        $I->seeCurrentUrlMatches('%edit%');
        $I->fillField('title', 'New name');
        $I->click('Update');

        $I->seeCurrentUrlMatches('%New\%20name-2002%');
        $I->seeInDatabase('animes', ['title' => 'New name']);
        $I->click('Delete');

        $I->seeCurrentUrlEquals('/anime');
        $I->dontseeInDatabase('animes', ['title' => 'New name']);
    }
}
