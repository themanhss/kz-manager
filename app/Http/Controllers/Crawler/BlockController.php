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

use App\Models\Post as Post;

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

	public function delete($block_id, Request $request)
	{
		if ($request->getMethod() == 'POST') {

			$block = Block::find($block_id);

			if ($block->delete()) {
				return redirect()->action('Crawler\BlockController@index');
			} else {
			}

		}

		$block = Block::find($block_id);
		return view('backend.block.delete',['block'=>$block]);
	}

	public function initConfig($db_name, $db_user, $db_password, $db_host){

		define('DB_NAME', $db_name);

		/** MySQL database username */
		define('DB_USER', $db_user);

		/** MySQL database password */
		define('DB_PASSWORD', $db_password);

		/** MySQL hostname */
		define('DB_HOST', $db_host);
	}

	public function runCraw($block_id){

		$block = Block::find($block_id);

		if($block_id == 4) {
			$this->initConfig('wordpress','root','','localhost');
		}

		if($block_id == 5){
			$this->initConfig('news','root','','localhost');
		}



//		define('DB_HOST', 'localhost');
		define('WP_USE_THEMES', false);

        require public_path().'/wordpress/wp-blog-header.php';

		/*$categories = \get_categories( array(
			'orderby' => 'name',
			'parent'  => 0
		) );

		dd($categories);*/

		$detail_news_pattern = $block->detail_a;
		$title_pattern = $block->title;
		$description_pattern = $block->content;
		$description_pattern_delete = $block->delete_item;

		$link =  $block->url;

		$html = new \Htmldom($link);

		$detail_item = $html->find($detail_news_pattern,0)->href;

		$post = array();

		$detail_link = new \Htmldom($detail_item);

		// Get Title
		foreach($detail_link->find($title_pattern) as $element)
		{
			$post['title'] = trim($element->plaintext); // Chỉ lấy phần text
		}

		// Get main content

		foreach($detail_link->find($description_pattern) as $element)
		{

			// Xóa các mẫu trong miêu tả
			if($description_pattern_delete){
				$arr = explode(',',$description_pattern_delete);
				for($j=0;$j<count($arr);$j++){
					foreach($element->find($arr[$j]) as $e){
						$e->outertext='';
					}
				}
			}

			$post['content'] = $element->innertext; // Lấy toàn bộ phần html

			// Find all images
			foreach($element->find('img') as $img) {
				$img_link = $img->src;
				$img_link = strtok($img_link, '?');

				$url_arr = explode ('/', $img_link);
				$ct = count($url_arr);
				$name = $url_arr[$ct-1];

				// $name = iconv("UTF-8", "ISO-8859-1", $name);


				copy($img_link, $name);

				/*//Get the file
                $content = file_get_contents($img_link);


                //Store in the filesystem.
                $fp = fopen($name, "w");
                fwrite($fp, $content);
                fclose($fp);*/

			};


		}

		$val = array(
			'post_title'    => $post['title'],
			'post_content'  => $post['content'],
			'post_status'   => 'publish',
			'post_author'   => 1,
			'post_category' => array( 2 )
		);

		$posts = \wp_insert_post($val);
		if($posts) {
			return redirect()->action('Crawler\BlockController@index');
		}
	}
}
