<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

// use App\Models\Post;

Route::get('/', 'GuestController@welcome');

Route::get('/order/create', 'OrderPageController@create')->name('create.order');
Route::post('/order/store', 'OrderPageController@store')->name('store.order');
Route::view('/order/success', 'successorder');
Route::get('/order/getproduct/{name}', 'OrderPageController@getProduct')->name('get.order');
Route::get('/order/show/{invoice}', 'OrderPageController@show')->name('show.order');

Route::get('/paymentpage/create', 'PaymentPageController@create')->name('create.payment');
Route::post('/paymentpage/store', 'PaymentPageController@store')->name('store.payment');

Route::get('/about', function () {
    return view('about');
});
Route::view('/price', 'price');
Route::get('/story', 'GuestController@listEvent');
Route::get('/blog', 'BlogController@index')->name('index.blog');
Route::get('/blog/{slug}', 'BlogController@show')->name('show.blog');
Route::get('/register', 'RegisterController@showRegistrationForm')->name('register');
Route::get('/template/choco', function () {
    return view('template-1');
});

Route::get('/template/pink', function () {
    return view('template-2');
});

Route::get('/template/blue', function () {
    return view('template-3');
});

Route::get('/template/grey', function () {
    return view('template-4');
});


Route::get('/landingpages', 'GuestController@landingpages');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/preview/{invoice}/', 'HomeController@preview')->name('preview.event');

Route::get('/payment/{invoice}/', 'Customer\PaymentController@confirmation')->name('payment.confirmation');
Route::post('/payment/{invoice}/confirmed', 'Customer\PaymentController@confirmed')->name('payment.confirmed');

Route::get('/{slug?}', 'GuestController@view')->name('see.event');
Route::get('{slug?}/event/builder', 'GuestController@basiceventpage')->name('event-builder');
Route::get('/{slug?}/guestbook', 'GuestController@guestbook')->name('see.guestbook');
Route::get('/{slug?}/thankyou', 'GuestController@thankyou')->name('see.thankyou');
Route::get('/s/{id}', 'GuestController@redirect');
Route::post('/attending/{id}', 'GuestController@attending')->name('attending');
Route::post('/wishes/{id}', 'GuestController@wishes')->name('wishes');

Route::get('/demo/gold', 'GuestController@demoGold')->name('demo.gold');
Route::get('/demo/soft', 'GuestController@demoSoft')->name('demo.soft');
Route::get('/demo/prime', 'GuestController@demoPrime')->name('demo.prime');
Route::get('/demo/silver', 'GuestController@demoSilver')->name('demo.silver');
Route::get('/demo/choco', 'GuestController@demoChoco')->name('demo.choco');
Route::get('/demo/pink', 'GuestController@demoPink')->name('demo.pink');
Route::get('/demo/crystal', 'GuestController@demoCrystal')->name('demo.crystal');
Route::get('/demo/grey', 'GuestController@demoGrey')->name('demo.grey');
Route::get('/demo/bronze', 'GuestController@demoBronze')->name('demo.bronze');
Route::get('/demo/blue', 'GuestController@demoBlue')->name('demo.blue');

Route::get('/demo/v1', 'GuestController@demoV1')->name('demo.v1');
Route::get('/demo/v2', 'GuestController@demoV2')->name('demo.v2');
Route::get('/demo/v3', 'GuestController@demoV3')->name('demo.v3');
Route::get('/demo/v4', 'GuestController@demoV4')->name('demo.v4');
Route::get('/demo/v5', 'GuestController@demoV5')->name('demo.v5');
Route::get('/demo/v6', 'GuestController@demoV6')->name('demo.v6');
Route::get('/demo/v7', 'GuestController@demoV7')->name('demo.v7');






/**
 * Route Admin
 */
Route::namespace('Admin')->prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {


    /**
     * CRUD sttings
     */
    Route::get('/settings', 'SettingController@index')->name('settings');
    /**
     * CRUD User
     */
    Route::get('/user', 'UserController@index')->name('users');
    Route::post('/user/{id}/update', 'UserController@update')->name('update.user');
    Route::post('/user/create', 'UserController@store')->name('create.user');
    Route::get('/user/{id}/delete', 'UserController@destroy')->name('delete.user');
    Route::get('/profile', 'UserController@profile')->name('profile');
    Route::post('/profile/update', 'UserController@updateProfile')->name('update.profile');

    /**
     * CRUD Customer
     */
    Route::get('/customer', 'CustomerController@index')->name('list.customer');
    Route::get('/customer/create', 'CustomerController@create')->name('create.customer');
    Route::post('/customer/store', 'CustomerController@store')->name('store.customer');
    Route::get('/customer/{id}/edit', 'CustomerController@edit')->name('edit.customer');
    Route::post('/customer/{id}/update', 'CustomerController@update')->name('update.customer');
    Route::get('/customer/{id}/delete', 'CustomerController@destroy')->name('delete.customer');
    Route::get('/customer/{id}/show', 'CustomerController@show')->name('show.customer');

    /**
     * CRUD Role & Permission
     */
    Route::get('/role', 'UserController@listRole')->name('list.role');
    Route::post('/role/create', 'UserController@createRole')->name('create.role');
    Route::get('/role/{id}/edit', 'UserController@editRole')->name('edit.role');
    Route::post('/role/{id}/update', 'UserController@updateRole')->name('update.role');
    Route::get('/role/{id}/delete', 'UserController@deleteRole')->name('delete.role');

    Route::get('/permission', 'UserController@listPermission')->name('list.permission');
    Route::post('/permission/create', 'UserController@createPermission')->name('create.permission');
    Route::post('/permission/{id}/update', 'UserController@updatePermission')->name('update.permission');
    Route::get('/permission/{id}/delete', 'UserController@deletePermission')->name('delete.permission');

    /**
     * CRUD Categories
     */
    Route::get('/categories', 'CategoryController@index')->name('list.category');
    Route::post('/categories/create', 'CategoryController@store')->name('create.category');
    Route::post('/categories/{id}/update', 'CategoryController@update')->name('update.category');
    Route::get('/categories/{id}/delete', 'CategoryController@destroy')->name('delete.category');

    /**
     * CRUD audio
     */
    Route::get('/audio', 'AudioController@index')->name('list.audio');
    Route::post('/audio/create', 'AudioController@store')->name('create.audio');
    Route::post('/audio/{id}/update', 'AudioController@update')->name('update.audio');
    Route::get('/audio/{id}/delete', 'AudioController@destroy')->name('delete.audio');

    /** *
     * CRUD Banks
     *
     */

    Route::get('/banks', 'BankController@index')->name('list.banks');
    Route::post('/banks/create', 'BankController@store')->name('create.bank');
    Route::get('/banks/getbank/{id}', 'BankController@getBankById');
    Route::post('/banks/{id}/edit/', 'BankController@update')->name('edit.bank');
    Route::get('/banks/{id}/delete/', 'BankController@destroy')->name('delete.bank');

    /** *
     * CRUD Banks
     *
     */

    Route::get('/newsletter', 'NewsletterController@index')->name('list.newsletter');
    Route::get('/newsletter/create', 'NewsletterController@create')->name('create.newsletter');
    Route::post('/newsletter/store', 'NewsletterController@store')->name('store.newsletter');
    Route::get('/newsletter/retryQueueStore', 'NewsletterController@retryQueueStore')->name('queue.store.newsletter');
    Route::get('/newsletter/{id}/show', 'NewsletterController@show')->name('show.newsletter');
    Route::post('/groupcontact/store', 'NewsletterController@storeGroupContact')->name('store.groupcontact');

    /**
     * CRUD Products
     */
    Route::get('/products', 'ProductController@index')->name('list.product');
    Route::get('/products/create', 'ProductController@create')->name('create.product');
    Route::post('/products/store', 'ProductController@store')->name('store.product');
    Route::get('/products/{id}/edit', 'ProductController@edit')->name('edit.product');
    Route::post('/products/{id}/update', 'ProductController@update')->name('update.product');
    Route::get('/products/{id}/delete', 'ProductController@destroy')->name('delete.product');

    /**
     * CRUD Orders
     */
    Route::get('/orders', 'OrderController@index')->name('list.order');
    Route::get('/orders/create', 'OrderController@create')->name('create.order');
    Route::post('/orders/store', 'OrderController@store')->name('store.order');
    Route::get('/orders/{invoice}/edit', 'OrderController@edit')->name('edit.order');
    Route::post('/orders/{id}/update', 'OrderController@update')->name('update.order');
    Route::get('/orders/{id}/delete', 'OrderController@destroy')->name('delete.order');
    Route::get('/orders/{invoice}/show', 'OrderController@show')->name('show.order');
    Route::get('/payments', 'PaymentController@index')->name('list.payment');

    /**
     * CRUD Events
     */
    Route::get('/events', 'EventController@index')->name('list.event');
    Route::get('/events/create', 'EventController@create')->name('create.event');
    Route::post('/events/store', 'EventController@store')->name('store.event');
    Route::post('/events/{id}/photo/store', 'EventController@storePhoto')->name('store.photo');
    Route::get('/events/{invoice}/edit', 'EventController@edit')->name('edit.event');
    Route::post('/events/{id}/update', 'EventController@update')->name('update.event');
    Route::get('/events/{id}/delete', 'EventController@destroy')->name('delete.event');
    Route::get('/photo/{id}/delete', 'EventController@destroyPhotoEvent')->name('delete.photo.event');
    Route::get('/events/{invoice}/show', 'EventController@show')->name('show.event');
    Route::get('/events/pdf/{invoice}', 'EventController@createPDF')->name('pdf.event');
    Route::get('/photo/{id}/avatar/wanita', 'EventController@deletePhotoWanita')->name('delete.photo.wanita');
    Route::get('/photo/{id}/avatar/pria', 'EventController@deletePhotoPria')->name('delete.photo.pria');
    Route::get('events/builder/{slug}', 'EventController@buildEvent')->name('event.build');
    Route::post('events/builder/{slug}/store', 'EventController@storeEventPage')->name('event.build.store');

    /**
     * CRUD Post
     */
    Route::get('/post/search', 'PostController@search')->name('search.post');
    Route::get('/post', 'PostController@index')->name('list.post');
    Route::get('/post/create', 'PostController@create')->name('create.post');
    Route::get('/post/show/{slug}', 'PostController@show')->name('show.post');
    Route::post('/post/store', 'PostController@store')->name('store.post');
    Route::get('/post/edit/{id}', 'PostController@edit')->name('edit.post');
    Route::post('/post/update/{id}', 'PostController@update')->name('update.post');
    Route::get('/post/delete/{id}', 'PostController@destroy')->name('delete.post');


    /**
     * Log Activity
     */
    Route::get('/logactivity', 'LogActivityController@index')->name('list.log');
    // Route::get('/logactivity/download', 'LogActivityController@export')->name('export.log');
    Route::post('/logactivity/getdata', 'LogActivityController@data')->name('ambil.data.log');

    /**
     * CRUD Angpao
     */

    Route::post('angpao/store', 'AngpaoController@store')->name('store.angpao');
    Route::post('angpao/{id}/update', 'AngpaoController@update')->name('update.angpao');
    Route::get('angpao/{id}/delete', 'AngpaoController@destroy')->name('delete.angpao');


    /**
     * Guest Book
     */
    Route::get('/ucapan/{id}/delete', 'EventController@deleteGuestBook')->name('delete.ucapan');
});

/**
 * Route Customer
 */
Route::namespace('Customer')->prefix('customer')->name('customer.')->middleware('role:customer')->group(function () {

    /**
     * Profile
     */
    Route::get('/profile', 'UserController@profile')->name('profile');
    Route::post('/profile/update', 'UserController@updateProfile')->name('update.profile');
    /**
     * CRUD Orders
     */
    Route::get('/orders', 'OrderController@index')->name('list.order');
    Route::get('/orders/create', 'OrderController@create')->name('create.order');
    Route::post('/orders/store', 'OrderController@store')->name('store.order');
    Route::get('/orders/{invoice}/edit', 'OrderController@edit')->name('edit.order');
    Route::post('/orders/{id}/update', 'OrderController@update')->name('update.order');
    Route::get('/orders/{id}/delete', 'OrderController@destroy')->name('delete.order');
    Route::get('/orders/{invoice}/show', 'OrderController@show')->name('show.order');

    /**
     * CRUD Events
     */
    Route::get('/photo/{id}/avatar/wanita', 'EventController@deletePhotoWanita')->name('delete.photo.wanita');
    Route::get('/photo/{id}/avatar/pria', 'EventController@deletePhotoPria')->name('delete.photo.pria');
    Route::get('/events', 'EventController@index')->name('list.event');
    Route::get('/events/create', 'EventController@create')->name('create.event');
    Route::post('/events/store', 'EventController@store')->name('store.event');
    Route::post('/events/{id}/photo/store', 'EventController@storePhoto')->name('store.photo');
    Route::get('/events/{invoice}/edit', 'EventController@edit')->name('edit.event');
    Route::post('/events/{id}/update', 'EventController@update')->name('update.event');
    Route::get('/events/{id}/delete', 'EventController@destroy')->name('delete.event');
    Route::get('/photo/{id}/delete', 'EventController@destroyPhotoEvent')->name('delete.photo.event');
    Route::get('/events/{invoice}/show', 'EventController@show')->name('show.event');
    Route::post('/invite/event/{id}', 'EventController@add')->name('add.guest');
    Route::post('/write/guestbook', 'EventController@writeGuestBook')->name('write.guestbook');
    Route::get('/events/pdf/{invoice}', 'EventController@createPDF')->name('pdf.event');
    Route::get('events/builder/{slug}/', 'EventController@buildEvent')->name('event.build');
    Route::post('events/builder/{slug}/store', 'EventController@storeEventPage')->name('event.build.store');

    /**
     * CRUD Angpao
     */

    Route::post('angpao/store', 'AngpaoController@store')->name('store.angpao');
    Route::post('angpao/{id}/update', 'AngpaoController@update')->name('update.angpao');
    Route::get('angpao/{id}/delete', 'AngpaoController@destroy')->name('delete.angpao');

    /**
     * Guest Book
     */
    Route::get('/ucapan/{id}/delete', 'EventController@deleteGuestBook')->name('delete.ucapan');
});
