<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */
Route::group(['middleware' => 'tracker'], function () {
    Route::get('/', 'Frontend\HomeController@index')->name('frontend_home');
    Route::get('/home', 'Frontend\HomeController@index')->name('frontend_home_alias');
    Route::get('/about/{promotion_id}', 'Frontend\HomeController@about')->name('frontend_about');
    Route::get('/contact/{promotion_id}', 'Frontend\HomeController@contact')->name('frontend_contact');
    Route::post('/contact/{promotion_id}', 'Frontend\HomeController@contact')->name('frontend_contact_post');
    Route::post('/callback/{promotion_id}', 'Frontend\HomeController@callback')->name('frontend_contact_callback_post');
    Route::get('/promotion/process/{promotion_id}', 'Frontend\PromotionController@process')->name('frontend_promotion_process');
    Route::get('/promotion/{promotion_slug}/{promotion_id}', 'Frontend\PromotionController@index')->name('frontend_promotion_index');

    Route::get('/product/compare/{promotion_id}', 'Frontend\ProductController@compare')->name('frontend_product_compare');
    Route::get('/product/{product_slug}/{product_id}', 'Frontend\ProductController@index')->name('frontend_product_index');
    Route::get('/product/printing', 'Frontend\ProductController@printing')->name('frontend_product_printing');
    Route::get('/product/comparison', 'Frontend\ProductController@comparison')->name('frontend_product_comparison');
    Route::get('/product/comparison2', 'Frontend\ProductController@comparison2')->name('frontend_product_comparison2');

    Route::get('/health/status', 'Frontend\HealthController@status');
    Route::get('/health/test', 'Frontend\HealthController@test');

    Route::post('tracking', 'Visitor\VisitorController@tracking')->name('tracking');
});

/*
  |--------------------------------------------------------------------------
  | Authentication
  |--------------------------------------------------------------------------
 */
// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');
Route::any('auth/forgot-password', 'Auth\PasswordController@forgot');
Route::any('auth/new-password/{token}', 'Auth\PasswordController@newPassword');
/*
  |--------------------------------------------------------------------------
  | Backend
  |--------------------------------------------------------------------------
 */
View::composer('layout.backend.master', 'App\Http\Composer\MainTemplateComposer');

Route::group(['prefix' => 'admin'], function () {
    // Admin User
    Route::get('users', 'User\Backend\UserController@index');
    Route::any('users/create', 'User\Backend\UserController@create');
    Route::get('users/profile/{id}', 'User\Backend\UserController@profile', ['middleware' => 'auth'])->where('id', '[0-9]+');
    Route::any('users/edit/{id}', 'User\Backend\UserController@edit', ['middleware' => 'auth'])->where('id', '[0-9]+');
    Route::any('users/delete/{id}', 'User\Backend\UserController@delete', ['middleware' => 'auth'])->where('id', '[0-9]+');


    // Customer
    Route::get('customers', 'Customer\Backend\CustomerController@index');
    Route::any('customers/create', 'Customer\Backend\CustomerController@create');
    Route::any('customers/import', 'Customer\Backend\CustomerController@importCustomer', ['middleware' => 'auth']);
    Route::get('customers/profile/{id}', 'Customer\Backend\CustomerController@profile', ['middleware' => 'auth'])->where('id', '[0-9]+');
    Route::any('customers/edit/{id}', 'Customer\Backend\CustomerController@edit', ['middleware' => 'auth'])->where('id', '[0-9]+');
    Route::any('customers/delete/{id}', 'Customer\Backend\CustomerController@delete', ['middleware' => 'auth'])->where('id', '[0-9]+');

    // API customer
    Route::get('all-customer', 'Customer\Backend\CustomerController@allCustomer');
    Route::any('get-customer', 'Customer\Backend\CustomerController@getCustomer');

    // Promotion
    Route::get('promotions', 'Promotion\Backend\PromotionController@index');
    Route::any('promotions/create', 'Promotion\Backend\PromotionController@create');
    Route::any('promotions/edit/{id}', 'Promotion\Backend\PromotionController@edit');
    Route::any('promotions/delete/{id}', 'Promotion\Backend\PromotionController@delete');
    Route::any('promotions/profile/{id}', 'Promotion\Backend\PromotionController@profile')->name('promotion.profile');
    Route::any('promotions/chart', 'Promotion\Backend\PromotionController@getViewChart');


    // Manage Product in Promotion
    Route::any('promotions/{promotion_id}/product/create', 'Promotion\Backend\PromotionController@createProduct');
    Route::any('promotions/{promotion_id}/product/edit/{product_id}', 'Promotion\Backend\PromotionController@editProduct');
    Route::any('promotions/{promotion_id}/product/delete', 'Promotion\Backend\PromotionController@deleteProduct');
    Route::any('promotions/{promotion_id}/product/page/{product_id}', 'Promotion\Backend\PromotionController@editPageProduct');
    Route::any('promotions/{promotion_id}/product/page/{product_id}', 'Promotion\Backend\PromotionController@editPageProduct');
    Route::any('promotions/{promotion_id}/product/{product_id}/pricing', 'Promotion\Backend\PromotionController@editProductPricing');

    // Manage Lead in Promotion
    Route::any('promotions/{promotion_id}/lead/create', 'Promotion\Backend\PromotionController@createLead');
    Route::any('promotions/{promotion_id}/lead/edit/{product_id}', 'Promotion\Backend\PromotionController@editLead');
    Route::any('promotions/{promotion_id}/lead/delete', 'Promotion\Backend\PromotionController@deleteLead');
    Route::any('promotions/{promotion_id}/lead/{lead_id}/view', 'Promotion\Backend\PromotionController@viewLead');

    // Manage Campaign in Promotion
    Route::any('promotions/{promotion_id}/campaign/sms/create', 'Promotion\Backend\PromotionController@createSMSCampaign');
    Route::any('promotions/{promotion_id}/campaign/edit/{campaign_id}', 'Promotion\Backend\PromotionController@editCampaign');
    Route::any('promotions/{promotion_id}/campaign/delete', 'Promotion\Backend\PromotionController@deleteCampaign');
    Route::any('promotions/campaign/{campaign_id}', 'Promotion\Backend\PromotionController@showCampaign')->name('campaign_show');

    // Manage Page Content in Promotion
    Route::any('promotions/{promotion_id}/page/edit', 'Promotion\Backend\PromotionController@editPage');

    // Manage Lead
    Route::any('leads', 'Lead\Backend\LeadController@index');

    // Manager Theme
    Route::get('themes', 'Theme\Backend\ThemeController@index');
    Route::any('themes/create', 'Theme\Backend\ThemeController@create');
    Route::any('themes/edit/{id}', 'Theme\Backend\ThemeController@edit');
    Route::any('themes/delete', 'Theme\Backend\ThemeController@delete');
    Route::any('themes/uploadImage', 'Theme\Backend\ThemeController@uploadImage');

    // Manager Report
    Route::get('reports', 'Report\Backend\ReportController@index');
    Route::any('reports/pie-chart', 'Report\Backend\ReportController@pieChart');

    // Manage Theme Pricing
    Route::any('themes/{id}/pricing', 'Theme\Backend\ThemeController@editPricing');

    // Leads
    Route::any('leads', 'Lead\Backend\LeadController@index');

    //Admin dashboard
    Route::get('/', ['middleware' => 'auth', 'uses' => 'Dashboard\Admin\DashboardController@index']);

//    Route::get('home', [    'middleware' => 'auth',    'uses' => 'HomeController@index']);

    Route::get('dashboard', 'Dashboard\Admin\DashboardController@index');
});
/*
  |--------------------------------------------------------------------------
  | Frontend
  |--------------------------------------------------------------------------
 */
Route::group(['prefix' => 'user'], function () {
    Route::get('/', 'User\Frontend\UserController@index');
    Route::get('/profile/{id}', 'User\Frontend\UserController@profile')->where('id', '[0-9]+');
});




