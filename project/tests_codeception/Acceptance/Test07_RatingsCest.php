<?php

namespace TestsCodeception\Acceptance;

use Codeception\Util\Locator;
use Exception;
use TestsCodeception\Support\AcceptanceTester;

class Test07_RatingsCest
{
    public function ratingsFilterGenre(AcceptanceTester $I)
    {
        $I->wantTo('test choosing genre');
        $I->amOnPage('/ratings');
        $I->seeElement(Locator::find('select', ['name' => 'genre']));
        $num_of_elements = 0;
        while (true) {
            try {
                $I->seeElement("select[name=genre] option:nth-child($num_of_elements)");
                $num_of_elements++;
            } catch (Exception $e) {
                break;
            }
        }
        for ($i=2;$i<=$num_of_elements;$i++) {
            $I->amOnPage('/ratings');
            $option2 = $I->grabTextFrom("select[name=genre] option:nth-child($i)");
            $I->selectOption("select[name=genre]", $option2);
            $I->seeOptionIsSelected('form select[name=genre]', $option2);
            $I->click("Filter");
            $I->seeCurrentUrlEquals("/ratings/calculate");
            $elements = $I->grabMultiple('a');
            foreach ($elements as $element) {
                $I->click($element);
                $I->see("Genre: $option2");
                $I->moveBack(1);
            }
        }
    }
    public function ratingsFilterYear(AcceptanceTester $I)
    {
        $I->wantTo('test choosing year');
        $I->amOnPage("/ratings");
        $I->seeElement(Locator::find('select', ['name' => 'production_year']));
        $num_of_elements = 0;
        while (true) {
            try {
                $I->seeElement("select[name=production_year] option:nth-child($num_of_elements");
                $num_of_elements++;
            } catch (Exception $e) {
                break;
            }
        }
        for ($i=2;$i<=$num_of_elements;$i++) {
            $I->amOnPage('/ratings');
            $option1 = $I->grabTextFrom("select[name=production_year] option:nth-child($i)");
            $I->selectOption("select[name=production_year]", $option1);
            $I->seeOptionIsSelected("form select[name=production_year]", $option1);
            $I->click("Filter");
            $I->seeCurrentUrlEquals("/ratings/calculate");
            $elements = $I->grabMultiple('a');
            foreach ($elements as $element) {
                $I->click($element);
                $I->see("Production year: $option1");
                $I->moveBack(1);
            }
        }
    }
    public function ratingsFilterGenreAndYear(AcceptanceTester $I)
    {
        $I->wantTo('test choosing both year and genre');
        $I->amOnPage("/ratings");
        $I->seeElement(Locator::find('select', ['name' => 'genre']));
        $I->seeElement(Locator::find('select', ['name' => 'production_year']));
        $num_of_elements1 = 1;
        $num_of_elements2 = 1;
        while (true) {
            try {
                $I->seeElement("select[name=genre] option:nth-child($num_of_elements1)");
                $num_of_elements1++;
            } catch (Exception $e) {
                break;
            }
        }
        while (true) {
            try {
                $I->seeElement("select[name=production_year] option:nth-child($num_of_elements2)");
                $num_of_elements2++;
            } catch (Exception $e) {
                break;
            }
        }
        for ($i=2; $i<$num_of_elements1;$i++) {
            for ($j = 2; $j < $num_of_elements2; $j++) {
                $I->amOnPage('/ratings');
                $option1 = $I->grabTextFrom("select[name=production_year] option:nth-child($j)");
                $I->selectOption("select[name=production_year]", $option1);
                $I->seeOptionIsSelected("form select[name=production_year]", $option1);
                $option2 = $I->grabTextFrom("select[name=genre] option:nth-child($i)");
                $I->selectOption("select[name=genre]", $option2);
                $I->seeOptionIsSelected('form select[name=genre]', $option2);
                $I->click("Filter");
                $I->seeCurrentUrlEquals("/ratings/calculate");
                $elements = $I->grabMultiple('a');
                foreach ($elements as $element) {
                    $I->click($element);
                    $I->see("Production year: $option1");
                    $I->see("Genre: $option2");
                    $I->moveBack(1);
                }
            }
        }
    }
    public function ratingsFilterAll(AcceptanceTester $I)
    {
        $I->amOnPage('/ratings');
        $I->selectOption("select[name=production_year]", 'all');
        $I->seeOptionIsSelected("form select[name=production_year]", 'all');
        $I->selectOption("select[name=genre]", 'all');
        $I->seeOptionIsSelected('form select[name=genre]', 'all');
        $I->click("Filter");
        $I->seeCurrentUrlEquals("/ratings/calculate");
        $elements = $I->grabMultiple('a');
        $I->seeNumRecords(count($elements), 'animes');
    }
}
