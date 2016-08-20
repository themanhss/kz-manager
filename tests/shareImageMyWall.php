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
       $this->webDriver->quit();
    }
    public function testShareToGroup()
    {
        $gmails = App\Models\Gmail::where('type',0)->get();

        foreach ($gmails as $key => $gmail) {
            $redirect_to = 'https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=https://accounts.google.com';
            $this->webDriver->get($redirect_to);
            sleep(3);
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

            //$captions = file(public_path().'/google-communities/captions.txt', FILE_IGNORE_NEW_LINES);

            $this->webDriver->get('https://plus.google.com/');
            sleep(3);
            // Put content to input
            $this->webDriver->manage()->timeouts()->implicitlyWait(10);
            $this->webDriver->findElement(WebDriverBy::className('kqa'))->click();

            /* Share Image */
            sleep(2);
            $this->webDriver->findElement(WebDriverBy::className('JI'))->click();

            sleep(2);
            $this->webDriver->findElement(WebDriverBy::className('fya'))->click();

            // Get rand img
            $result= $this->webDriver->findElements(WebDriverBy::className('a-nf-e-nb'));
            $count_result=count($result);

            $rand_index =  rand (0 , $count_result );
            $img_rand = 'a-nf-e-nb:nth-child('.$rand_index.')';

            sleep(1);
            $this->webDriver->findElement(WebDriverBy::className($img_rand))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::className('a-Qb-e-D6'))->click();


            sleep(1);
            $this->webDriver->manage()->timeouts()->implicitlyWait(10);
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::id('picker:ap:2'))->click();

            sleep(1);
            $this->webDriver->findElement(WebDriverBy::className('editable'))->click();

            // get Rand Caption
            //$caption = $captions[rand(0, count($captions))];

            //$this->webDriver->findElement(WebDriverBy::className('editable'))->sendKeys($caption);
            $this->webDriver->findElement(WebDriverBy::className('editable'))->click();
            $this->webDriver->executeScript("window.scrollTo(0, 300);", []);

            sleep(3);
            $this->webDriver->manage()->timeouts()->implicitlyWait(10);
            $this->webDriver->findElement(WebDriverBy::className('b-c-Ba'))->click();

        }

    }
}