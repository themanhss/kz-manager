<?php

use Carbon\Carbon;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

class autoPostBlogspot extends Illuminate\Foundation\Testing\TestCase {

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
       //$this->webDriver->quit();
    }

    public function testPostBlogspot()
    {
        $gmails = App\Models\Gmail::all();

        foreach ($gmails as $key => $gmail) {

            $url = 'http://kz-manager.com/admin/gmails/' . $gmail->id . '/blogspots';

            if ($key > 0) {
                $redirect_to = 'https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=' . $url;
                $this->webDriver->get($redirect_to);
            }else{
                $this->webDriver->get($url);
            }

            if ($key == 0) {

                $username = $this->webDriver->findElement(WebDriverBy::id('Email'));
                $username->sendKeys('1');

                /*$this->webDriver->manage()->timeouts()->implicitlyWait(10);*/

                $pw = $this->webDriver->findElement(WebDriverBy::id("Password"));
                $pw->sendKeys("123456");

                $signIn = $this->webDriver->findElement(WebDriverBy::id('submitBtn'));
                $signIn->click();

            }


            sleep(3);

            // click get token Btn
            $getTocken = $this->webDriver->findElement(WebDriverBy::id('getTockenBtn'));
            $getTocken->click();

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


            sleep(2);
            // click "Cho phep" btn
            $this->webDriver->findElement(WebDriverBy::id('submit_approve_access'))->click();


            sleep(2);

            $this->webDriver->get($url);

            sleep(2);

            $this->webDriver->findElement(WebDriverBy::id('postAllBlog'))->click();

    //            sleep(30);
                sleep(5);

        }
    }

}
