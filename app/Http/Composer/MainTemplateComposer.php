<?php namespace App\Http\Composer;
use Session;
use App\Company;
use Illuminate\Support\Facades\Auth;
class MainTemplateComposer{

    public function __construct()
    {
        # code...
    }
    public function compose($view){

        if (Auth::check())
        {
            $view->with('user', Auth::user());

        }

    }

}

?>
