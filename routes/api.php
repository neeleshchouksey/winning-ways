<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
header( 'Access-Control-Allow-Headers:Access-Control-Allow-Origin, Authorization, Content-Type' );
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('cors')->group(function(){

/*****************APIs for mobile application *******************/

Route::post('/signup', 'MobileApiController@signup');
Route::post('/user-login', 'MobileApiController@login');

Route::post('send-otp', 'MobileApiController@send_otp');
Route::post('check-otp', 'MobileApiController@check_otp');

Route::get('get-cities/{id}', 'MobileApiController@get_cities');
Route::get('get-states', 'MobileApiController@get_states');

Route::get('get-services', 'MobileApiController@get_services');
Route::get('get-service-details/{id}', 'MobileApiController@get_service_details');
Route::get('subcategory/{id}', 'MobileApiController@subcategory_details');

Route::post('send-forget-otp', 'MobileApiController@send_forget_otp');
Route::post('reset-password', 'MobileApiController@change_password');

Route::post('add-contact-us', 'MobileApiController@add_contact_us');


Route::middleware('auth:api')->group(function() {
    Route::post('work-post-by-user', 'MobileApiController@work_post_by_user');
    Route::post('claim-work-by-user', 'MobileApiController@claim_work_by_user');
    Route::get('/get-my-works/{id}', 'MobileApiController@get_my_works');
    Route::get('/get-my-claim-works/{id}', 'MobileApiController@get_my_claim_works');
    Route::get('/get-user-payments', 'MobileApiController@get_user_payments');
    Route::get('/get-debit-requests', 'MobileApiController@get_debit_requests');
    Route::post('/request-for-debit', 'MobileApiController@request_for_debit');

    Route::get('/get-packages', 'MobileApiController@get_packages');
    Route::get('/get-single-packages/{id}', 'MobileApiController@get_single_package');
    Route::get('/get-achievers', 'MobileApiController@get_achievers');

    Route::get('/purchase-package/{id}', 'MobileApiController@purchase_packages');


});
/*****************APIs for admin side*******************/

    Route::post('/login', 'AdminApiController@login');
    Route::get('logout', 'AdminApiController@logout');

    Route::middleware('auth:api')->group(function() {
        Route::post('/get-users', 'AdminApiController@get_users');
        Route::get('/get-recent-users', 'AdminApiController@get_recent_users');
        Route::post('/get-user-payments', 'AdminApiController@get_user_payments');
        Route::post('/get-debit-requests', 'AdminApiController@get_debit_requests');
        Route::post('/get-packages', 'AdminApiController@get_packages');
        Route::post('/add-package', 'AdminApiController@add_package');
        Route::get('/get-single-package/{id}', 'AdminApiController@get_single_package');
        Route::post('/update-package', 'AdminApiController@update_package');
        Route::get('/delete-package/{id}', 'AdminApiController@delete_package');
        Route::get('/get-recent-works', 'AdminApiController@get_recent_works');
        Route::post('/get-works', 'AdminApiController@get_works');
        Route::post('/get-claimed-works', 'AdminApiController@get_claimed_works');
        Route::post('/update-work', 'AdminApiController@update_work');
        Route::get('/close-work/{id}', 'AdminApiController@close_work');
        Route::get('/get-work-details/{id}', 'AdminApiController@get_work_details');
        Route::post('/get-works-user/{id}', 'AdminApiController@get_works_user');
        Route::post('/get-claimed-works-user/{id}', 'AdminApiController@get_claimed_works_user');
        Route::post('/get-work-followup/{id}', 'AdminApiController@get_work_followup');
        Route::post('/get-all-work-followup', 'AdminApiController@get_all_work_followup');
        Route::get('/delete-work-followup/{id}', 'AdminApiController@delete_work_followup');
        Route::post('/get-single-work-followup/{id}', 'AdminApiController@get_single_work_followup');
        Route::post('/take-work-followup/{id}', 'AdminApiController@take_work_followup');
        Route::post('/update-work-followup', 'AdminApiController@update_work_followup');
        Route::post('/change-status/{id}', 'AdminApiController@change_status');
        Route::post('/credit-amount', 'AdminApiController@credit_amount');
        Route::get('/export-user-account-statement', 'AdminApiController@export_user_account_statement');

        /************Category related apis*************/
        Route::post('/select-categories', 'AdminApiController@select_categories');
        Route::post('/get-categories', 'AdminApiController@get_categories');
        Route::post('/add-category', 'AdminApiController@add_category');
        Route::get('/get-single-category/{id}', 'AdminApiController@get_single_category');
        Route::post('/update-category', 'AdminApiController@update_category');
        Route::get('/delete-category/{id}', 'AdminApiController@delete_category');
        Route::post('/add-category-to-homepage','AdminApiController@add_category_to_homepage');

        /************Slider Main related apis*************/

        Route::post('/get-main-slider', 'AdminApiController@get_main_slider');
        Route::post('/add-main-slider', 'AdminApiController@add_main_slider');
        Route::get('/get-single-main-slider/{id}', 'AdminApiController@get_single_main_slider');
        Route::post('/update-main-slider', 'AdminApiController@update_main_slider');
        Route::get('/delete-main-slider/{id}', 'AdminApiController@delete_main_slider');

            /************Slider business related apis*************/

        Route::post('/get-business-slider', 'AdminApiController@get_business_slider');
        Route::post('/add-business-slider', 'AdminApiController@add_business_slider');
        Route::get('/get-single-business-slider/{id}', 'AdminApiController@get_single_business_slider');
        Route::post('/update-business-slider', 'AdminApiController@update_business_slider');
        Route::get('/delete-business-slider/{id}', 'AdminApiController@delete_business_slider');

       /************how we works related apis*************/

        Route::post('/get-how-we-work', 'AdminApiController@get_how_we_works');
        Route::post('/add-how-we-work', 'AdminApiController@add_how_we_work');

        /*************Get to know related apis***************/

        Route::post('/get-get-to-know-us', 'AdminApiController@get_get_to_know_us');
        Route::post('/add-get-to-know-us', 'AdminApiController@add_get_to_know_us');

        /*************about us related apis***************/

        Route::post('/get-about-us', 'AdminApiController@get_about_us');
        Route::post('/add-about-us', 'AdminApiController@add_about_us');

        /************Sub Category related apis*************/

        Route::post('/select-sub-categories/{id}', 'UserController@select_sub_categories');
        Route::post('/get-sub-categories', 'AdminApiController@get_sub_categories');
        Route::post('/add-sub-category', 'AdminApiController@add_sub_category');
        Route::get('/get-single-sub-category/{id}', 'AdminApiController@get_single_sub_category');
        Route::post('/update-sub-category', 'AdminApiController@update_sub_category');
        Route::get('/delete-sub-category/{id}', 'AdminApiController@delete_sub_category');

        Route::get('/get-cat-sub-edit', 'AdminApiController@get_cat_sub_edit');
        Route::get('/get-cat-sub', 'AdminApiController@get_cat_sub');
        Route::get('/get-cat-sub-edit/{id}', 'AdminApiController@get_cat_sub_edit');


        /*********************All work package related apis***************/

        Route::post('/get-all-work-packages', 'AdminApiController@get_all_work_packages');
        Route::post('/add-work-packages', 'AdminApiController@add_work_packages');
        Route::get('/get-single-work-packages/{id}', 'AdminApiController@get_single_work_packages');
        Route::post('/update-work-packages', 'AdminApiController@update_work_packages');
        Route::get('/delete-work-packages/{id}', 'AdminApiController@delete_work_packages');


 /*********************All subcategory video related apis***************/

        Route::post('/get-videos/{id}', 'AdminApiController@get_videos');
        Route::post('/add-video', 'AdminApiController@add_video');
        Route::get('/get-single-video/{id}', 'AdminApiController@get_single_video');
        Route::post('/update-video', 'AdminApiController@update_video');
        Route::get('/delete-video/{id}', 'AdminApiController@delete_video');


        Route::post('/get-contact-data', 'AdminApiController@get_contact_data');

        Route::post('/get-package-requests', 'AdminApiController@get_package_requests');
        Route::get('/accept-package-request/{id}', 'AdminApiController@accept_package_request');


        /************Blog related apis*************/
        Route::post('/get-blogs', 'AdminApiController@get_blogs');
        Route::post('/add-blog', 'AdminApiController@add_blog');
        Route::get('/get-single-blog/{id}', 'AdminApiController@get_single_blog');
        Route::post('/update-blog', 'AdminApiController@update_blog');
        Route::get('/delete-blog/{id}', 'AdminApiController@delete_blog');

        /************Faq related apis*************/

        Route::post('/get-faqs', 'AdminApiController@get_faqs');
        Route::post('/add-faq', 'AdminApiController@add_faq');
        Route::get('/get-single-faq/{id}', 'AdminApiController@get_single_faq');
        Route::post('/update-faq', 'AdminApiController@update_faq');
        Route::get('/delete-faq/{id}', 'AdminApiController@delete_faq');

        /************Careers related apis*************/

        Route::post('/get-careers', 'AdminApiController@get_careers');
        Route::post('/add-career', 'AdminApiController@add_career');
        Route::get('/get-single-career/{id}', 'AdminApiController@get_single_career');
        Route::post('/update-career', 'AdminApiController@update_career');
        Route::get('/delete-career/{id}', 'AdminApiController@delete_career');

        /*************Dashboard related apis*************/

        Route::get('/get-total-data', 'AdminApiController@get_total_data');
        Route::get('/get-total-users/{id}', 'AdminApiController@get_total_users');
        Route::get('/get-total-works/{id}', 'AdminApiController@get_total_works');


        /*************about us related apis***************/

        Route::post('/get-data/{id}', 'AdminApiController@get_data');
        Route::post('/add-data/{id}', 'AdminApiController@add_data');

    });
//});



