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
    public function testShareToGroup()
    {
//        $links = App\Models\Link::where('created_at', '>=', Carbon::now()->subHours(24))->get();
        $links = App\Models\Link::all();
        $google_login = $redirect_to = 'https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=https://accounts.google.com';
        $this->webDriver->get($google_login);
        sleep(2);
        $username = $this->webDriver->findElement(WebDriverBy::id('Email'));
        if ($username->isDisplayed()) {
            $username->sendKeys('themanhss');
        }
        $next = $this->webDriver->findElement(WebDriverBy::id('next'));
        if ($next->isDisplayed()) {
            $next->click();
        }
        $this->webDriver->manage()->timeouts()->implicitlyWait(10);
        $pw = $this->webDriver->findElement(WebDriverBy::id("Passwd"));
        if ($pw->isDisplayed()) {
            $pw->sendKeys("themanh2311");
        }else{
            $this->webDriver->manage()->timeouts()->implicitlyWait(10);
            $pw->sendKeys("themanh2311");
        }
        $signIn = $this->webDriver->findElement(WebDriverBy::id('signIn'));
        if ($signIn->isDisplayed()) {
            $signIn->click();
        }
        sleep(2);
        foreach ($links as $link){
            $communities = file(public_path().'/google-communities/communities.txt', FILE_IGNORE_NEW_LINES);
            $number_communities = 5;
            $rand_keys = array_rand($communities, $number_communities);
            $coms = array();
            for ($i = 0; $i< $number_communities; $i++){
                array_push($coms, $communities[$rand_keys[$i]]);
            }
            foreach ($coms as $k => $community) {
                $url = $community;
                $this->webDriver->get($url);
                // Put content to input
                $this->webDriver->manage()->timeouts()->implicitlyWait(10);
                $this->webDriver->findElement(WebDriverBy::className('kqa'))->click();
                sleep(2);
                $this->webDriver->findElement(WebDriverBy::className('hL'))->click();
                sleep(2);
                $this->webDriver->manage()->timeouts()->implicitlyWait(10);
                $post_content = $this->webDriver->findElement(WebDriverBy::className('fm'));
                $post_content->sendKeys($link->url);
                sleep(1);
                $this->webDriver->findElement(WebDriverBy::className('editable'))->click();
                $string = '+ + + Cùng nhau lên top nhé các bạn :) + + +  '.$link->title;
                $this->webDriver->findElement(WebDriverBy::className('editable'))->sendKeys($string);
//                if($this->webDriver->manage()->window()->getSize()->getHeight() <= 800) {
                $this->webDriver->executeScript("window.scrollTo(0, 300);", []);
//                }
                sleep(10);
                $this->webDriver->manage()->timeouts()->implicitlyWait(10);
                $this->webDriver->findElement(WebDriverBy::className('b-c-Ba'))->click();
            }
        }
    }
}