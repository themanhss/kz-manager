<?php

namespace App\Http\Controllers\Crawler;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Blogspot as Blogspot;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Module;
use App\Models\Gmail as Gmail;
use App\Models\Blog as Blog;
use App\Models\Block as Block;
use App\Models\Blockdetail as Blockdetail;

class BlockController extends Controller {
	/*
	  |--------------------------------------------------------------------------
	  | Dashboard Controller
	  |--------------------------------------------------------------------------
	 */

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */


	public function __construct() {
		$this->middleware('auth');
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index() {
		$blocks = Block::all();
		return view('backend.block.index',['blocks'=> $blocks]);
	}

	/*
     *Create a Gmail Account
     *
     *@POST("/admin/gmails/create")
     *@Param: ({'firstName','lastName', 'email', 'password', '', 'isCompanyAdmin'})
     *@Version("v1")
     */
	public function create(Request $request)
	{
		if ($request->getMethod() == 'POST') {

			$datas = $request->all();

			/*Validation form*/
			$validator = Validator::make($request->all(), [
				'name' => 'required',
				'url' => 'required|url',
				'list_li' => 'required',
				'detail_a' => 'required',
				'title' => 'required',
				'content' => 'required'
			]);

			if ($validator->fails()) {
				return redirect('admin/blocks/create')
					->withErrors($validator)
					->withInput();
			}


			$block = new Block();

			$block->name = $datas['name'];
			$block->status = $datas['status'];
			$block->url = $datas['url'];
			$block->list_li = $datas['list_li'];
			$block->detail_a = $datas['detail_a'];

			$block->title = $datas['title'];
			$block->content = $datas['content'];
			$block->delete_item = $datas['delete_item'];

			if ($block->save()) {
				return redirect()->action('Crawler\BlockController@detailBlock',[$block->id]);
			} else {

			}

		}

		return view('backend.block.create');
	}

	/*
     * Edit a Gmail Account
     *
     *@POST("/admin/gmails/{$gmail_id}/edit")
     *@Param: ({'firstName','lastName', 'email', 'password', '', 'isCompanyAdmin'})
     *@Version("v1")
     */
	public function edit($block_id, Request $request)
	{
		if ($request->getMethod() == 'POST') {

			$datas = $request->all();

			/*Validation form*/
			$validator = Validator::make($request->all(), [
				'name' => 'required',
				'url' => 'required|url',
				'list_li' => 'required',
				'detail_a' => 'required',
				'title' => 'required',
				'content' => 'required'
			]);

			if ($validator->fails()) {
				return redirect('admin/blocks/'.$block_id.'/edit')
					->withErrors($validator)
					->withInput();
			}


			$block = Block::find($block_id);

			$block->name = $datas['name'];
			$block->status = $datas['status'];
			$block->url = $datas['url'];
			$block->list_li = $datas['list_li'];
			$block->detail_a = $datas['detail_a'];

			$block->title = $datas['title'];
			$block->content = $datas['content'];
			$block->delete_item = $datas['delete_item'];

			if ($block->save()) {
				return redirect()->action('Crawler\BlockController@detailBlock',[$block_id]);
			} else {

			}

		}

		$block = Block::find($block_id);
		return view('backend.block.edit',['block'=>$block]);
	}


	public function detailBlock($block_id, Request $request)
	{
		$block = Block::find($block_id);
		return view('backend.block.detail',['block'=>$block]);
	}


}
