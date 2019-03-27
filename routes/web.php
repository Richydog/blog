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

Route::get('/','HoumeController@index')->name('houme.show');
Route::get('/post/{slug}','HoumeController@show')->name('post.show');
Route::get('/tags/{slug}','HoumeController@tag')->name('tag.show');
Route::get('/category/{slug}','HoumeController@category')->name('category.show');
Route::post('/subscribe','SubsController@subscribe');
Route::get('/verify/{token}','SubsController@verify');




Route::group(['middleware'=>'auth'],function () {
    Route::get('/profile','ProfileController@index');
    Route::get('/logout', 'AuthController@logout');
    Route::post('/profile','ProfileController@store');
    Route::post('/comment', 'CommentsControlle@store');
});

Route::group(['middleware'=>'guest'],function () {
    Route::get('/register', 'AuthController@registerForm');
    Route::post('/register', 'AuthController@register');
    Route::get('/login', 'AuthController@loginForm')->name('login');
    Route::post('/login', 'AuthController@login');

});







//Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('form_for_test', 'FormController@index')->name('get_form');
Route::post('post_form', 'FormController@uploadForm')
    ->name('post_form')
    ->middleware('age');
Route::get('post/{postId}','FormController@showPost')->name('post');
Route::get('post/{postId}/coments/{comentsId}','FormController@showComents')->name('coment');
//Route::post('kroko', 'FormController@store');
Route::get('kroko', 'FormController@kroko');

Route::get('place','PlaceController@index');
Route::post('kroko','PlaceController@store');

Route::group(['prefix'=>'admin','namespace'=>'Admin','middleware'=>'admin'],function (){
    Route::get('/','DashboardController@index');
    Route::resource('/categories','CategoriesController');
    Route::resource('/tags','TagsController');
    Route::resource('/users','UsersController');
    Route::resource('/posts','PostsController');
    Route::get('/comments','CommentsControlle@index');
    Route::get('/comments/toggle/{id}','CommentsControlle@toggle');
    Route::get('/posts/toggle/{id}','PostsController@toggle');

    Route::delete('/comments/{id}/destroy','CommentsControlle@destroy')->name('comments.destroy');
    Route::resource('/subscribers', 'SubscribersController');
});
