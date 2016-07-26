<?php

use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

/**
 * Description of testAdminLogin
 *
 * @author linhnp
 */
class testGooglePlus extends Illuminate\Foundation\Testing\TestCase {

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

         $links = App\Models\Link::all();
         

        foreach ($links as $key=>$link) {

        $url = 'https://plus.google.com/share?url='.$link->url;
        $this->webDriver->get($url);

           if($key == 0) {

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
           }
          /*end check $k=0*/
           

           $this->webDriver->findElement(WebDriverBy::className('b-c-Ba'))->click();
        }

    }


}
