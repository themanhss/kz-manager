<?php
use Carbon\Carbon;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
class shareToGroup extends Illuminate\Foundation\Testing\TestCase {
    /**
     * @var RemoteWebDriver
     */
    protected $webDriver;
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';
        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        return $app;
    }
    public function setUp()
    {
        $app = require __DIR__.'/../bootstrap/app.php';
        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        $this->webDriver = RemoteWebDriver::create('http://localhost:4444/wd/hub', DesiredCapabilities::chrome());
    }
    public function tearDown()
    {
//       $this->webDriver->quit();
    }
    public function testJoinGroup()
    {
        $communities = file(public_path().'/google-communities/communities.txt', FILE_IGNORE_NEW_LINES);

            $redirect_to = 'https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=https://accounts.google.com';
            $this->webDriver->get($redirect_to);





            // auth google
            $username = $this->webDriver->findElement(WebDriverBy::id('Email'));
            if ($username->isDisplayed()) {
                $username->sendKeys('longbien.love09@gmail.com');
            }

            $this->webDriver->manage()->timeouts()->implicitlyWait(5);
            $next = $this->webDriver->findElement(WebDriverBy::id('next'));
            if ($next->isDisplayed()) {
                $next->click();
            }


            $pw = $this->webDriver->findElement(WebDriverBy::id("Passwd"));
            $this->webDriver->manage()->timeouts()->implicitlyWait(10);
            $pw->sendKeys('themanh2311');

            $signIn = $this->webDriver->findElement(WebDriverBy::id('signIn'));
            if ($signIn->isDisplayed()) {
                $signIn->click();
            }


            foreach ($communities as $ck=> $community) {
                $this->webDriver->get($community);

                sleep(1);

                if (count( $this->webDriver->findElements(WebDriverBy::className('XCc') )) != 0) {
                    $this->webDriver->findElement(WebDriverBy::className('XCc'))->click();
                    sleep(1);
                }

            }


    }
}