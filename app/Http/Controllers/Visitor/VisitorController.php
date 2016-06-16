<?php
namespace App\Http\Controllers\Visitor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    public function __construct()
    {

    }

    /**
     * Handle set cookie tracking user from front-end ajax request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return application/json
     */
    public function tracking(Request $request)
    {
        return response()->json(['message' => 'Email is saved', 'status' => '200'])
            ->withCookie(cookie('email_tracker', $request->input('email'), (86400 * 30), '/'));
    }

}
