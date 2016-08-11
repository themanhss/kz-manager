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
        $gmails = App\Models\Gmail::all();
        $communities = file(public_path().'/google-communities/communities.txt', FILE_IGNORE_NEW_LINES);

        foreach ($gmails as $key => $gmail) {

            $redirect_to = 'https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=https://accounts.google.com';
            $this->webDriver->get($redirect_to);

            sleep(3);

            // Dang nhap vao tai khoan khac tu lan thu 2

            if($key == 1){

                $this->webDriver->findElement(WebDriverBy::id('account-chooser-link'))->click();;

                sleep(2);

                $this->webDriver->findElement(WebDriverBy::id('account-chooser-add-account'))->click();
            }

            if($key > 1) {
                sleep(2);

                $this->webDriver->findElement(WebDriverBy::id('account-chooser-add-account'))->click();
            }




            // auth google
            $username = $this->webDriver->findElement(WebDriverBy::id('Email'));
            if ($username->isDisplayed()) {
                $username->sendKeys($gmail->gmail);
            }

            $this->webDriver->manage()->timeouts()->implicitlyWait(5);
            $next = $this->webDriver->findElement(WebDriverBy::id('next'));
            if ($next->isDisplayed()) {
                $next->click();
            }


            $pw = $this->webDriver->findElement(WebDriverBy::id("Passwd"));
            $this->webDriver->manage()->timeouts()->implicitlyWait(10);
            $pw->sendKeys($gmail->pw);

            $signIn = $this->webDriver->findElement(WebDriverBy::id('signIn'));
            if ($signIn->isDisplayed()) {
                $signIn->click();
            }


            foreach ($communities as $ck=> $community) {
                $this->webDriver->get($community);

                sleep(3);

                if (count( $this->webDriver->findElements(WebDriverBy::className('XCc') )) != 0) {
                    $this->webDriver->findElement(WebDriverBy::className('XCc'))->click();
                    sleep(3);
                }

            }

        }
    }
}