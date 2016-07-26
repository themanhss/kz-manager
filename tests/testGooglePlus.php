<?php

use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

use App\Models\Gmail as Gmail;
/**
 * Description of testAdminLogin
 *
 * @author linhnp
 */
class testGooglePlus extends PHPUnit_Framework_TestCase {

    /**
     * @var RemoteWebDriver
     */
    protected $webDriver;


    public function setUp()
    {

        $this->webDriver = RemoteWebDriver::create('http://localhost:4444/wd/hub', DesiredCapabilities::chrome());

    }

    public function tearDown()
    {
//        $this->webDriver->quit();
    }

    public function testAutoPlus()
    {
        $gmails = App\Models\Gmail::all();

        foreach ($gmails as $key=>$gmail) {

           $url = 'https://plus.google.com/share?url=http://kiza.vn/blog/danh-rieng-cho-nhung-co-nang-cuong-mau-xanh/';
           $this->webDriver->get($url);

           $username = $this->webDriver->findElement(WebDriverBy::id('Email'));
           $username->sendKeys('themanhss');

           $next = $this->webDriver->findElement(WebDriverBy::id('next'));
           $next->click();

           $this->webDriver->manage()->timeouts()->implicitlyWait(10);

           $this->webDriver->findElement(WebDriverBy::id("Passwd"))->sendKeys("themanh2311");

           $signIn = $this->webDriver->findElement(WebDriverBy::id('signIn'));
           $signIn->click();


           $this->webDriver->findElement(WebDriverBy::className('b-c-Ba'))->click();
        }




    }



}