<?php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
})->middleware('guest');
Route::get('/login', function () {
    return view('auth');
})->middleware('guest');
Route::post('/login', "Auth\LoginController@login");
Route::get('/logout', 'Auth\LoginController@logout');
Route::group(['middleware' => 'auth', 'prefix' => 'web'], function () {
    Route::get('/me', 'Admin\UsersController@me');
    Route::get('/dashboard', 'Admin\DashboardController@index');
    Route::get('/is-unique', 'Admin\UsersController@unique');
    Route::get('/orders/count', 'Admin\OrderController@orderCount');
    Route::get('/orders/sales', 'Admin\OrderController@salesReport');
    Route::post('/orders/track', 'Admin\OrderController@updateStatus');
    Route::apiResources([
        'categories' => 'Admin\CategoryController',
        'items' => 'Admin\ItemController',
        'orders' => 'Admin\OrderController',
        'reports' => 'Admin\ReportController',
        'users' => 'Admin\UsersController',
    ]);
});

Route::get('/{any?}', 'Admin\SpaController')->where('any', '[\/\w\.-]*');
