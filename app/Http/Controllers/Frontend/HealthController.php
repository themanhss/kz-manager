<?php 
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class HealthController extends Controller
{
	public function __construct()
	{
            
	}
	public function status()
	{
		echo '200';
	}
	
	public function test(){
		echo 'Testing123';
	}

}
