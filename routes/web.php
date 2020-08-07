<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Artisan;

Route::get('/call-artisan',function(){
    $exitCode = Artisan::call('storage:link', [] );
    echo $exitCode;
});

//Route::get('/{any}', 'HomeController@index')->where('any', '.*');
Route::get('/', 'HomeController@index')->name("user");
Route::get('/signin', 'HomeController@user_login')->name("signin");
Route::get('/signup', 'HomeController@user_signup');
Route::post('login', 'HomeController@login');
Route::get('logout', 'HomeController@logout');
Route::get('achievers', 'HomeController@achievers');
Route::post('add-contact-us', 'HomeController@add_contact_us');
Route::post('signup', 'HomeController@signup');
Route::get('privacy-and-cookie-policy', 'HomeController@privacy_policy');
Route::get('terms-and-conditions', 'HomeController@terms_conditions');
Route::get('careers', 'HomeController@careers');
Route::get('faqs', 'HomeController@faqs');
Route::get('blogs', 'HomeController@blogs');
Route::get('blog-details/{id}', 'HomeController@blog_details');
Route::get('about-us', 'HomeController@about_us');
Route::get('contact-us', 'HomeController@contact_us');
Route::get('get-cities/{id}', 'HomeController@get_cities');
Route::post('get-achievers', 'HomeController@get_achievers');
Route::get('get-states', 'HomeController@get_states');
Route::get('get-blogs', 'HomeController@get_blogs');
Route::post('send-forget-otp', 'HomeController@send_forget_otp');
Route::post('send-otp', 'HomeController@send_otp');
Route::post('send-email', 'HomeController@send_email');
Route::post('check-otp', 'HomeController@check_otp');
Route::post('reset-password-otp', 'HomeController@change_password_otp');
Route::post('reset-password', 'HomeController@change_password');
Route::get('reset-password/{id}', 'HomeController@reset_password');
Route::get('/packages', 'UserController@packages');
Route::get('/categories', 'HomeController@categories');
Route::get('/category/{id}', 'HomeController@category_details');
Route::get('/subcategory/{id}', 'HomeController@subcategory_details');
Route::get('/get-packages', 'UserController@get_packages');
Route::post('/get-single-package/{id}', 'UserController@get_single_package');

Route::middleware('auth')->group(function() {

    Route::get('/my-account', 'UserController@my_account');
    Route::get('/my-works', 'UserController@my_works');
    Route::get('/my-claimed-works', 'UserController@my_claimed_works');
    Route::get('/get-user-payments', 'UserController@get_user_payments');
    Route::get('/get-debit-requests', 'UserController@get_debit_requests');
    Route::get('/debit-requests', 'UserController@debit_requests');
    Route::post('/request-for-debit', 'UserController@request_for_debit');
    Route::get('/get-my-works/{id}', 'UserController@get_my_works');
    Route::get('/get-my-claim-works/{id}', 'UserController@get_my_claim_works');
    Route::get('/purchase-package/{id}', 'UserController@purchase_packages');
    Route::post('/select-categories', 'AdminApiController@select_categories');
    Route::post('/select-sub-categories/{id}', 'UserController@select_sub_categories');
    Route::get('/work-post', 'UserController@work_post');
    Route::get('/claim-work', 'UserController@claim_work');
    Route::post('work-post-by-user', 'UserController@work_post_by_user');
    Route::post('claim-work-by-user', 'UserController@claim_work_by_user');
    Route::get('/export-user-account-statement', 'UserController@export_user_account_statement');

});
