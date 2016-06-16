<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Visitors as Visitor;
use Illuminate\Support\Facades\Cookie;

class TrackerVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // set cookie to tracking visited page
        if($request->input('cemail') and !$request->ajax()) {
            $response->withCookie(cookie('email_tracker', $request->input('email'), (86400 * 30), '/'));
        }

        return $response;

    }

    /**
     * Handle tracking user after response end.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return mixed
     */
    public function terminate($request, $response)
    {
        // update email for IP
        $client_ip = $request->getClientIp();
        $email_tracker = null;

        // Get email from cookie or GET param (?email={email})
        if(Cookie::get('email_tracker')) {
            $email_tracker = Cookie::get('email_tracker');
        }else if($request->input('email')) {
            $email_tracker = $request->input('email');
        }

        $visitor_page = \Request::ajax() === true ? str_replace(\URL::to('/') . "/", '', $request->server('HTTP_REFERER'))  : $request->path();

        // Update previous record by Client IP
        if($email_tracker) {
            Visitor::where('visitor_ip', $client_ip)->where('visitor_page', $visitor_page)->update(['visitor_email' => $email_tracker]);
        }

        // if is ajax request then get URL path from full URL

        $visitor = new Visitor();
        $visitor->visitor_email = $email_tracker;
        $visitor->visitor_ip = $client_ip;
        $visitor->visitor_browser = $request->server('HTTP_USER_AGENT');
        $visitor->visitor_date = new \DateTime();
        $visitor->visitor_referral = $request->server('HTTP_REFERER');
        $visitor->visitor_page = $visitor_page;

        $visitor->save();
    }
}
