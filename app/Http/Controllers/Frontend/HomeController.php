<?php 
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;
use Mail;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Lead;
use App\Models\Client;

class HomeController extends Controller
{
	public function __construct()
	{
		
	}
	public function index()
	{
		return redirect()->route('frontend_promotion_index', [ getPromotionSlug(0), getPromotionKey(0) ]);

//		return view('frontend/home', array( 'calculatorData' => $calculatorData ));
	}

	public function about($promotionId)
	{
		$data = array();

		$promotionIdx = getPromotionIdx($promotionId);
		if ( $promotionIdx !== false )
		{
			if ( $promotionIdx === 1 )
				$data['showNav2'] = true;

			return view('frontend/about', $data);
		}

		return response()->view('errors.404', [], 404);
	}

	public function contact(Request $request, $promotionId)
	{
		$tempConfig = getTempConfig();
		$promotionIdx = getPromotionIdx($promotionId);
		if ( $promotionIdx === false )
		{
			return response()->view('errors.404', [], 404);
		}

		$first_name = '';
		$first_name_error = false;
		$last_name = '';
		$last_name_error = false;
		$email = '';
		$email_error = false;
		$phone = '';
		$vehicles = array();
		$vehicles_error = false;
		$message = '';
		$message_error = false;
		$form_error = false;
		$form_success = false;

		if ($request->isMethod('post'))
		{
			$first_name = $request->input('first_name', '');
			$last_name = $request->input('last_name', '');
			$email = $request->input('email', '');
			$phone = $request->input('phone', '');
			$vehicles = $request->input('vehicles', array());
			$message = $request->input('message', '');

			if ( trim($first_name) == '' )
			{
				$first_name_error = true;
				$form_error = true;
			}
			if ( trim($last_name) == '' )
			{
				$last_name_error = true;
				$form_error = true;
			}
			if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) )
			{
				$email_error = true;
				$form_error = true;
			}
			if ( empty($vehicles) )
			{
				$vehicles_error = true;
				$form_error = true;
			}
			if ( trim($message) == '' )
			{
				$message_error = true;
				$form_error = true;
			}

			if ( !$form_error )
			{
				$client = Client::where('email', $email)->first();
				$clientId = 0;
				if ( $client != null && isset( $client->id ) )
				{
					$clientId = $client->id;
				}
				else
				{
					$newClient = new Client();
					$newClient->first_name = $first_name;
					$newClient->last_name = $last_name;
					$newClient->email = $email;
					$newClient->mobile_phone = $phone;
					$newClient->home_phone = $phone;
					$newClient->save();
					$clientId = $newClient->id;
				}

				$promotion = Promotion::where('promotion_key', $promotionId)->first();
				$products = Product::whereIn('product_key', $vehicles)->get();
				$vehicles = array();
				$vehiclesNames = array();

				foreach ( $products as $prod )
				{
					$vehicles[] = $prod->product_id;
					$vehiclesNames[] = $prod->name;
				}

				$lead = new Lead();
				$lead->client_id = $clientId;
				$lead->promotion_id = isset($promotion->promotion_id) ? $promotion->promotion_id : 1;
				$lead->message = $message;
				$lead->email = $email;
				$lead->phone = $phone;
				$lead->source_type = 'Website Enquiry';
				$lead->date = date('Y-m-d');
				$lead->product_ids = json_encode($vehicles);
				$lead->save();

				$to = 'richard@saltandfuessel.com.au, Rebecca.Dowling@swanninsurance.com.au';
//				$to = 'andrei.testing.now@gmail.com';

				$subject = 'Message from Contact Page';

				$headers = "From: " . $email . " \r\n";
				$headers .= "Reply-To: ". $email . " \r\n";
				$headers .= "CC: iagservices@iag.com.au" . " \r\n";
				$headers .= "BCC: andrei.testing.now@gmail.com" . " \r\n";
				$headers .= "MIME-Version: 1.0 \r\n";
				$headers .= "Content-Type: text/html; charset=UTF-8 \r\n";

				$body = '<html><body><div>';
				$body .= 'First Name: ' . $first_name . '<br/>';
				$body .= 'Last Name: ' . $last_name . '<br/>';
				$body .= 'Email: ' . $email . '<br/>';
				$body .= 'Phone: ' . $phone . '<br/>';
				$body .= 'Vehicles: ' . implode(', ', $vehiclesNames) . '<br/>';
				$body .= 'Message: ' . nl2br($message);
				$body .= '</div></body></html>';

				$r = mail($to, $subject, $body, $headers);

				if ( $r )
				{
					$form_success = true;
					$form_error = false;
					$first_name = '';
					$last_name = '';
					$email = '';
					$phone = '';
					$vehicles = array();
					$message = '';
				}
				else
				{
					$form_success = false;
					$form_error = true;
				}
			}
		}

		$data = array(
			'promotionIdx' => $promotionIdx,
			'promotionData' => $tempConfig[ getPromotionKey ( $promotionIdx ) ],
			'first_name' => $first_name,
			'first_name_error' => $first_name_error,
			'last_name' => $last_name,
			'last_name_error' => $last_name_error,
			'email' => $email,
			'email_error' => $email_error,
			'phone' => $phone,
			'vehicles' => $vehicles,
			'vehicles_error' => $vehicles_error,
			'message' => $message,
			'message_error' => $message_error,
			'form_error' => $form_error,
			'form_success' => $form_success
		);

		if ( $promotionIdx === 1 )
			$data['showNav2'] = true;

		return view('frontend/contact', $data);
	}

	public function callback(Request $request)
	{
		$has_error = false;
		$form_error = 'Generic Error';

		if ($request->isMethod('post'))
		{
			$name = $request->input('name', '');
			$phone = $request->input('phone_number', '');
			$time = $request->input('time', '');
			$date = $request->input('date', '');

			if ( trim($name) == '' )
			{
				$form_error = 'Please enter your name';
				$has_error = true;
			}
			else if ( trim($phone) == '' )
			{
				$form_error = 'Please enter your phone number';
				$has_error = true;
			}
			else if ( trim($time) == '' )
			{
				$form_error = 'Please enter the time';
				$has_error = true;
			}
			else if ( trim($date) == '' )
			{
				$form_error = 'Please enter the date';
				$has_error = true;
			}

			if ( $has_error )
			{

			}
			else
			{
				$to = 'richard@saltandfuessel.com.au, Rebecca.Dowling@swanninsurance.com.au';

				$subject = 'Request for callback';

				$headers = "From: no-reply@iag.com" . "\r\n";
				$headers .= "Reply-To: no-reply@iag.com" . " \r\n";
				$headers .= "CC: iagservices@iag.com.au" . " \r\n";
				$headers .= "BCC: andrei.testing.now@gmail.com" . " \r\n";
				$headers .= "MIME-Version: 1.0 \r\n";
				$headers .= "Content-Type: text/html; charset=UTF-8 \r\n";

				$body = '<html><body><div>';
				$body .= 'Name: ' . $name . '<br/>';
				$body .= 'Phone: ' . $phone . '<br/>';
				$body .= 'Time: ' . $time . '<br/>';
				$body .= 'Date: ' . $date;
				$body .= '</div></body></html>';

				$r = mail($to, $subject, $body, $headers);

				if ( $r )
				{
					$has_error = false;
				}
				else
				{
					$form_error = 'An error occured. Please try again.';
					$has_error = true;
				}
			}
		}

		return response()->json( array( 'status' => $has_error ? 'error' : 'ok', 'message' => $has_error ? $form_error : 'Thanks for your message!' ) );
	}
}
