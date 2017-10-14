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


Route::post('/publish', 'MqttController@mass_noty');
Route::post('/emqhook', 'MqttController@emqhook');
Route::get('/subscribe', 'MqttController@subscribe');



Route::get('/', function () {
    return view('admin/login');
});
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    // return what you want
});

Route::get('login', function () {
    return view('admin/login');
});

Route::get('register', function () {
    return view('admin/register');
});

Route::post('register','Admincontroller@register');

Route::post('login', 'Admincontroller@login');

Route::get('categories', 'Admincontroller@categories');

Route::get('settings', 'Admincontroller@setting');

Route::get('dashboard', 'Admincontroller@dashboard');

Route::post('settings/{squirrel}', 'Admincontroller@setting');

Route::post('categories/{squirrel}', 'Admincontroller@categories');

Route::get('users', 'Admincontroller@users');
Route::get('users/search', 'Admincontroller@users_search');

Route::get('categories/{squirrel}', 'Admincontroller@categories');
Route::get('categories/{squirrel}/{any}', 'Admincontroller@categories');

Route::get('users_ajax/{squirrel}', 'Admincontroller@users');
Route::get('users_ajax/{squirrel}/{any}', 'Admincontroller@users');

Route::post('getUser', 'Admincontroller@getUser');

Route::get('SendNoty', 'Admincontroller@main');

Route::get('Location_Noty', 'Admincontroller@Location_Noty');

Route::get('SendGroupNoty', 'Admincontroller@SendGroupNoty');
Route::get('SendCatNoty', 'Admincontroller@SendCatNoty');

Route::get('search_cat', 'Admincontroller@search_cat');


Route::post('admin/groups/{squirrel}', 'Admincontroller@groups');

Route::post('users/send_noty', 'Admincontroller@send_noty');
Route::post('users/send_user_noty', 'Admincontroller@send_user_noty');

Route::post('users/mass_noty', 'Admincontroller@mass_noty');

Route::post('users/mass_noty_loc', 'Admincontroller@mass_noty_loc');

Route::get('categories_ajax', 'Admincontroller@categories_ajax');

Route::get('groups', 'Admincontroller@groups');
Route::get('groups/{squirrel}', 'Admincontroller@groups');
Route::get('groups/{squirrel}/{any}', 'Admincontroller@groups');

Route::post('groups/{squirrel}', 'Admincontroller@groups');

Route::post('user/register', 'Usercontroller@register');

Route::post('user/upate', 'Usercontroller@update');

Route::get('groups', 'Admincontroller@groups');

Route::get('user/categories', 'Usercontroller@categories');



Route::get('logout', function () {
    Session::flush();
    return view('admin/login');
});

