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

    Route::get('dashboard', 'Dashboard\Admin\DashboardController@index');
    Route::get('', 'Dashboard\Admin\DashboardController@index');



    // Gmail Manager
    Route::get('gmails', 'Gmail\GmailController@index');
    Route::get('gmails/create', 'Gmail\GmailController@create');

    Route::get('gmails/{gmail_id}/blogspots', 'Gmail\GmailController@blogspot');




});
/*
  |--------------------------------------------------------------------------
  | Frontend
  |--------------------------------------------------------------------------
 */




