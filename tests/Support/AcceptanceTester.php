<?php

declare(strict_types=1);

namespace Tests\Support;

use Exception;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

 /**
     * @param string $username
     * @param string $password
     */
    public function login($username, $password)
    {
        $I = $this;

        $I->fillField('LoginForm[username]', $username);
        $I->fillField('LoginForm[password]', $password);
        $I->click('login-button');
        $I->wait(3);
    }

    public function loginAsAdmin()
    {
        $this->login('admin', '123456');
    }

    public function loginAsCoach()
    {
        $this->login('coach', '123456');
    }

    public function loginAsAssisstant()
    {
        $this->login('assisstant', '123456');
    }

    public function logout()
    {
        $I = $this;

        $I->click(".//span[@class = 'glyphicon glyphicon-user']/..");
        $I->wait(1);
        $I->click('Salir');
        $I->wait(1);
    }

    /**
     * @param string $mainMenuItem Text of main menu entry
     * @param string $secondaryMenuItem Text of menu entry child of main menu entry
     * @param string $tercearyMenuItem Text of menu entry child of previous menu entry
     */
    public function clickMainMenu(
        $mainMenuItem,
        $secondaryMenuItem = '',
        $tercearyMenuItem = ''
    ) {
        $this->wait(1);
        if ($secondaryMenuItem === '') {
            $mainAnchor = "//ul[@id='navbar']/li/a[contains(text(), '$mainMenuItem')]";
            $this->click($mainAnchor);
        } else {
            $mainAnchor = "//ul[@id='navbar']/li/a[contains(@class, 'dropdown-toggle') and contains(text(), '$mainMenuItem')]";
            $this->click($mainAnchor);

            $secondaryAnchor = "$mainAnchor/../ul/li/a[contains(text(), '$secondaryMenuItem')]";
            $this->click($secondaryAnchor);
        }
        if ($tercearyMenuItem !== '') {
            $tercearyAnchor = "$secondaryAnchor/../ul/li/a[contains(text(), '$tercearyMenuItem')]";
            $this->click($tercearyAnchor);
        }
    }

    /**
     * @param AcceptanceTester $I AcceptanceTester object used in codeception test
     * @param string $mainMenuItem Text of main menu entry
     * @param string $secondaryMenuItem Text of menu entry child of main menu entry
     * @param string $tercearyMenuItem Text of menu entry grand child of main menu entry
     * @param string $checkTitles string expected to be visible in title
     * @param string $inPageTag Tag of string expected to be visible in page
     */
    function checkMenuItem(
        $mainMenuItem,
        $secondaryMenuItem = '',
        $tercearyMenuItem = '',
        $checkTitles = true,
        $inPageTag = 'h1'
    ) {
        $I = $this;

        $I->clickMainMenu($mainMenuItem, $secondaryMenuItem, $tercearyMenuItem);
        $this->wait(1);

        $I->dontSee('#500');
        $I->dontSee('#404');
        $I->dontSee('error');
        $I->dontSee('Error');
        $I->dontSee('exception');
        $I->dontSee('Exception');
        $I->dontSee('Stack trace');

        if ($checkTitles) {
            if ($tercearyMenuItem != '') {
                $I->seeInTitle($tercearyMenuItem);
                $I->see($tercearyMenuItem, $inPageTag);
            } elseif ($secondaryMenuItem != '') {
                $I->seeInTitle($secondaryMenuItem);
                $I->see($secondaryMenuItem, $inPageTag);
            }
        }
    }

    public function selectOptionForSelect2($select, $option)
    {
        $menu = "//select[@name='$select']/../span/span[@class='selection']/span/span[@class='select2-selection__arrow']";
        $this->waitForElementVisible($menu, 5); // secs
        $this->click($menu);
        try {
            $this->waitForElementVisible('.select2-search__field', 5);
            $this->presskey('.select2-search__field', $option);
            $action =
                '(//li[contains(@class, \'select2-results__option\')])[1]';
            $this->waitForElementVisible($action, 5); // secs
            $this->click($action);
        } catch (Exception $e) {
            $this->click(".//li[text() = '$option']");
        }
    }
}
