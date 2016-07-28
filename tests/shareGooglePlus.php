<?php

use Carbon\Carbon;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;


class shareGooglePlus extends Illuminate\Foundation\Testing\TestCase {

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
       $this->webDriver->quit();
    }

    public function testAutoPlus()
    {
        $links = App\Models\Link::where('created_at', '>=', Carbon::now()->subHours(24))->get();

        $gmails = App\Models\Gmail::all();

        foreach ($gmails as $key => $gmail) {

            /*Login to google*/

            if ($key > 0) {
                $redirect_to = 'https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=https://accounts.google.com';
                $this->webDriver->get($redirect_to);
            }else{
                $this->webDriver->get('https://accounts.google.com');
            }

            sleep(2);

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
            $pw->sendKeys("themanh2311");


            $signIn = $this->webDriver->findElement(WebDriverBy::id('signIn'));
            if ($signIn->isDisplayed()) {
                $signIn->click();
            }


            // Share to google plus
            foreach ($links as $key=>$link) {

                $url = 'https://plus.google.com/share?url='.$link->url;
                $this->webDriver->get($url);

                $this->webDriver->findElement(WebDriverBy::className('b-c-Ba'))->click();
            }
            // end foreach link

        }
        // end foreach gmail
    }


}
